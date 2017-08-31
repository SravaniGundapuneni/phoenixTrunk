<?php

namespace Designer\Controller;

//use Zend\View\Model\JsonModel;

//============================================================================================================================
class SocketsController extends \ListModule\Controller\SocketsController
{
    static $con;

    //========================================================================================================================
    public function __construct()
    {
//        self::$con = mysqli_connect('localhost','root','','htb_phoenixtng');
        self::$con = mysqli_connect('10.30.25.218','trafficdbuser','9$9c8DdE','htb_phoenixtng');
        parent::__construct();
    }

    //========================================================================================================================
    public function savePageAction()
    {
        $pg = self::$con->real_escape_string(filter_input(INPUT_POST, 'page'));
        $cwd = getcwd();
        file_put_contents("$cwd/module/pages/view/pages/templates/$pg.phtml", htmlspecialchars_decode($_POST['html']));
        $res = self::$con->query("SELECT id FROM blocks WHERE blockKey='$pg'");
        $rows = $res->num_rows;
        if (!$res->num_rows) {
            self::$con->query("INSERT INTO blocks SET blockKey='$pg', template='pages/templates/$pg'");
            $bid = self::$con->insert_id;
            self::$con->query("INSERT INTO pages SET pageKey='$pg', blocks='{\"$bid\":\"content\"}'");
        }
        return $this->getResponse()->setContent('');
    }

    //========================================================================================================================
    public function getTemplateAction()
    {
        $view = new \Zend\View\Model\ViewModel;
        $view->setTerminal(true);
        $view->setTemplate('google-places/toolbox/designer');
        $view->toolboxIncludeUrl = $this->getServiceLocator()->get('MergedConfig')->get(array('paths', 'toolboxIncludeUrl'));
        return $view;
    }

    //========================================================================================================================
    public function getItemsAction()
    {
        $category = filter_input(INPUT_GET, 'category');
        $sql = $category == 1
            ? "SELECT pageId id, pageKey name FROM pages pg"
            : "SELECT id, displayName name FROM blocks WHERE category=$category";
        $res = self::$con->query($sql);
        $out = array();
        while ($out[]=$res->fetch_assoc());
        array_pop($out);
        return $this->getResponse()->setContent(json_encode($out));
    }

    //========================================================================================================================
    public function loadPageAction()
    {
        $GLOBALS['tc_current_page_id'] = $id = $this->params()->fromPost('id', null);
        $sql = "SELECT pg.blocks FROM pages pg WHERE pg.pageId=$id";
        $res = self::$con->query($sql);
        $r = $res->fetch_array();
        $blocks = implode(',', array_keys(json_decode($r[0], 1)));
        $sql = "SELECT bl.blocks, bl.template FROM blocks bl WHERE bl.id IN ($blocks)";
        $res = self::$con->query($sql);
        $r = $res->fetch_row();
        $cwd = getcwd();
        $tmpl = file_get_contents("$cwd/module/pages/view/$r[1].phtml");
        // prepair any dynamic data required
        if (preg_match('/::setDynamicData\(array\((.*?)\)/', $tmpl, $m)) {
            $svloc = $this->getServiceLocator();
            foreach (explode(',', str_replace('"', '', $m[1])) as $key) {
                if (!$key) {
                    continue;
                }
                $parts = explode('_', $key);  // service name/method name
                $method = "dyna$parts[1]";
                $data[$key] = $svloc->get($parts[0])->$method();
            }
//            $tmpl = preg_replace('/\<\?php.*?setDynamicData.*?\?\>/i', "<script>var dynamicData=".json_encode($data)."</script>", $tmpl);
        }
        return $this->getResponse()->setContent($tmpl);
    }

    //========================================================================================================================
    public function getBlockAction()
    {
        $sql = "SELECT * FROM blocks WHERE id=" . $this->params()->fromPost('id', null);
        $res = self::$con->query($sql);
        $r = $res->fetch_assoc();
        $cwd = getcwd();
        $tmpl = file_get_contents("$cwd/module/pages/view/$r[template].phtml");
        return $this->getResponse()->setContent($tmpl);
    }
}
