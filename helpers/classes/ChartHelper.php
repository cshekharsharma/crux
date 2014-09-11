<?php

class ChartHelper {

    /**
     * Get valid JSON for creating line charts
     *
     * @param array $series
     * @param array $xAxis
     * @param array $yAxis
     * @return string
     */
    public function getLineChartJson(array $series, array $xAxis, array $yAxis, array $config) {
        $json = array();
        foreach ($series as $key => $values) {
            if (is_array($values)) {
                $array = array(
                    'name' => $key,
                    'data' => $values
                );
                $json[] = $array;
            }
        }
        $schema = $this->getChartSchema();
        $schema['series'] = $json;
        $schema['xAxis']['categories'] = $xAxis;
        $schema['yAxis']['title']['text'] = $yAxis['title'];
        $schema['title']['text'] = $config['title'];
        return json_encode($schema);
    }

    /**
     * Returns basic schema for charts
     *
     * @return array $schema
     */
    protected function getChartSchema() {
        $schema = array(
            'title'     => array('text' => '', 'x' => -20),
            'subtitle'  => array('text' => '', 'x' => -20),
            'xAxis'     => array('categories' => array()),
            'yAxis'     => array(
                'title' => array('text' => ''),
                'plotLines' => array(
                    '0' => array(
                        'value' => 0,
                        'width' => 1,
                        'color' => '#808080'
                    )
                )
            ),
            'tooltip'   => array('valueSuffix' => ''),
            'legend'    => array(
                'layout' => 'vertical',
                'align' => 'right',
                'borderWidth' => 0,
                'verticalAlign' => 'middle'
            ),
            'series'    => array(),
        );

        return $schema;
    }
}