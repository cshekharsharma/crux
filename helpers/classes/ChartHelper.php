<?php

/**
 * Chart Helper class for creating all the charts
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @since Sep 12, 2014
 */
class ChartHelper {

    /**
     * Get valid JSON for creating line charts
     *
     * @param array $series
     * @param array $xAxis
     * @param array $yAxis
     * @return string
     */
    public function getLineChartJson(array $config, array $series, array $axis) {
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
        $schema = $this->getLineChartSchema();
        $schema['series'] = $json;
        $schema['xAxis']['categories'] = $axis['xAxis'];
        $schema['yAxis']['title']['text'] = $axis['yAxis']['title'];
        $schema['title']['text'] = $config['title'];
        return json_encode($schema);
    }

    /**
     * Get valid JSON for creating pie charts
     *
     * @param array $config
     * @param array $series
     * @return string
     */
    public function getPieChartJson(array $config, array $series) {
        $json = array();
        foreach ($series as $key => $values) {
            $array = array($values[0], $values[1]);
            $json[] = $array;
        }
        $schema = $this->getPieChartSchema();
        $schema['series'][0]['data'] = $json;
        $schema['series'][0]['name'] = $config['series_name'];
        $schema['title']['text']  = $config['main_title'];
        return json_encode($schema);
    }

    /**
     * Returns basic schema for charts
     *
     * @return array $schema
     */
    protected function getLineChartSchema() {
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
            'plotOptions' => array(
                'line' => array(
                    'dataLabels' => array(
                        'enabled' => false
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

    /**
     * Returns basic schema for PIE charts
     *
     * @return array
     */
    protected function getPieChartSchema() {
        $schema = array(
            'chart' => array(
                'plotBorderWidth' => 1, //null
                'options3d' => array(
                    'enabled' => true,
                    'alpha' => 45,
                    'beta' => 0
                )
            ),
            'title' => array('text' => ''),
            'tooltip' => array('pointFormat' => '{series.name}: <b>{point.percentage:.1f}%</b>'),
            'plotOptions' => array(
                'pie' => array(
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'depth'  => 35,
                    'dataLabels' => array(
                        'enabled' => true,
                        'format'  => '<b>{point.name}</b>: {point.percentage:.1f} %',
                        'style'   => 'white',
                    ),
                    'showInLegend' => true
                )
            ),
            'series' => array(
                array(
                    'type' => 'pie',
                    'name' => '',
                    'data' => array(
                        array(),
                    )
                )
            ),
        );
        return $schema;
    }
}