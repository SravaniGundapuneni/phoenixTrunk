<?php
namespace IHotelier\Classes\XMLGenerator;

class LeadRate extends XMLGenerator
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->responseType = 'LeadRate';
    }

    protected function buildHotelSearchCriteria($xml)
    {
        $xml->startElement('HotelSearchCriteria');
        $this->setAttribute($xml, 'AvailableOnlyIndicator');
            $xml->startElement('Criterion');

                $this->getHotel($xml, current($this->options['hotelCodes']));
                $this->getStayDateRange($xml);
                $this->getRooms($xml);

            $xml->endElement();
        $xml->endElement();
    }
}
