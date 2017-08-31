<?php
namespace Languages\Service;

use Languages\Model\Polytext;
use ListModule\Model\ListItem;

class Polytexts extends \ListModule\Service\Lists
{
    protected $langCode;

    public function __construct()
    {
        $this->entityName = Polytext::ENTITY_NAME;
        $this->modelClass = "\Languages\Model\Polytext";
    }

    /**
     * Initialize the service
     *
     * @param  string $langCode
     * @return null
     */
    public function init($langCode)
    {
        /**
         * Set the langCode to the Default if none given
         */
        if ($langCode == '')
        {
            $config_key = array('text', 'defaultLanguage', 'value');
            $langCode = $this->getConfig()->get($config_key, 'en');
        }

        $this->langCode = $langCode;
    }

    /**
     * Takes the given area and name, plus six customizable parameters, and builds
     * an array of values to be used by a template.
     *
     * This is effectively \Polytext::best(), but without the actual rendering of the template for the
     * polytext Element. There is a view helper called polytextElement which can take this array and render
     * the template.
     *
     * THIS IS STILL IN EARLY DEVELOPMENT, AND DOESN'T REPRESENT THE ENTIRETY OF WHAT THIS SHOULD DO
     * OR THE BEST WAY TO DO IT.
     *
     * @param  string $area
     * @param  string $name
     * @param  mixed $arg1
     * @param  mixed $arg2
     * @param  mixed $arg3
     * @param  mixed $arg4
     * @param  mixed $arg5
     * @param  mixed $arg6
     *
     * @return array
     */
    public function bestMatch($area, $name, $parameters = array())
    {
        $bestMatch = array();

        $contentAppearanceService = $this->serviceManager->get('phoenix-contentappearance');

        $bestMatch['textId'] = null;
        $bestMatch['textArea'] = $area;
        $bestMatch['textName'] = $name;
        $bestMatch['langCode'] = $this->langCode;
        $bestMatch['textValue'] = "[{$area}-{$name}]";
        $bestMatch['htmlMode'] = 'htmlAllow';
        $bestMatch['database'] = 'dbLocal';

        foreach($parameters as $key => $modifier)
        {
            switch ($modifier)
            {
                case 'htmlAllow':
                case 'htmlInline':
                case 'htmlStrip':
                case 'htmlNone':
                    $bestMatch['htmlMode'] = $modifier;
                    unset($parameters[$key]);
                    continue;
                    break;
                case 'dbLocal':
                case 'dbAdmin':
                    $bestMatch['database'] = $modifier;
                    unset($parameters[$key]);
                    continue;
                    break;
            }
        }

        /**
         * Lets return any extra parameters
         */
        $bestMatch = array_merge($bestMatch, $parameters);

        $entityManager = $this->defaultEntityManager;

        if ($bestMatch['database'] == 'dbAdmin') {
            $entityManager = $this->adminEntityManager;
        }

        /**
         * This will be replaced by code that actually uses Doctrine's ORM.
         */
        $polytext = $entityManager->getConnection()->fetchAssoc("
            SELECT * FROM polytext
            WHERE type = 'text' AND status = 1
            AND area = '{$bestMatch['textArea']}'
            AND name = '{$bestMatch['textName']}'
            AND lang = '{$bestMatch['langCode']}'
            ORDER BY created DESC LIMIT 1"
        );

        if ( ! is_null($polytext) && $polytext )
        {
            $bestMatch['textId'] = $polytext['textId'];
            $bestMatch['textValue'] = $polytext['text'];

            /**
             * Tie in to the content appearance tracking service
             */
            $contentAppearanceService->add(
                'text',
                "polytext::{$area}::{$name}",
                json_encode($bestMatch)
            );
        }

        return $bestMatch;
    }

    public function search($for)
    {
        $result = array();

        $for = str_replace(' ', '%', preg_replace('/\s+/', ' ', $for));

        if ( $for && strlen($for) > 3 )
        {
            $result = $this->defaultEntityManager->getConnection()->fetchAll("
                SELECT ca.page, pt.type, pt.text, pt.lang, pt.status
                FROM polytext pt INNER JOIN contentAppearance ca ON (
                    ca.contentKey = CONCAT('polytext','::',pt.area,'::',pt.name)
                    AND ca.contentType = pt.type
                    AND pt.type = 'text'
                    AND pt.status = 1
                )
                WHERE pt.text LIKE ?
                AND pt.lang = ?",
                array(
                    "%{$for}%",
                    $this->langCode
                )
            );
        }

        return $result ? $result : array();
    }
	
    public function searchNew($for)
    {
        $result = array();

        $for = str_replace(' ', '%', preg_replace('/\s+/', ' ', $for));

        if ( $for && strlen($for) > 2 )
        {
            $result = $this->defaultEntityManager->getConnection()->fetchAll("
                SELECT * FROM pages pt WHERE pt.pageKey LIKE '%{$for}%' OR pt.blocks LIKE '%{$for}%' OR pt.dataSection LIKE '%{$for}%'"
            );
        }

        return $result ? $result : array();
    }

    public function setLangCode($langCode)
    {
        $this->langCode = $langCode;
    }

    public function getLangCode()
    {
        return $this->langCode;
    }
}