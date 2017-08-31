<?php
namespace IHotelier\Classes\XMLGenerator;

class DailyRate extends XMLGenerator
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->responseType = 'DailyRate';
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
