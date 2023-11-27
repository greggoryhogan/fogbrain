<?php 
/**
 * This is built using an addon and as such is documented on the plugin repo, https://wordpress.org/plugins/advanced-custom-fields-table-field/#how%20to%20output%20the%20table%20html%3F
 */
$table = get_sub_field('table');
echo '<div class="flexible-content table">';
    if ( ! empty ( $table ) ) {
        echo '<table border="0">';
            if ( ! empty( $table['caption'] ) ) {
                echo '<caption>' . $table['caption'] . '</caption>';
            }
            if ( ! empty( $table['header'] ) ) {
                echo '<thead>';
                    echo '<tr>';
                        foreach ( $table['header'] as $th ) {
                            echo '<th>';
                                echo $th['c'];
                            echo '</th>';
                        }
                    echo '</tr>';
                echo '</thead>';
            }
            echo '<tbody>';
                foreach ( $table['body'] as $tr ) {
                    $index = 0;
                    echo '<tr>';
                        foreach ( $tr as $td ) {
                            $label = '';
                            if ( ! empty( $table['header'] ) ) {
                                $label = $table['header'][$index]['c'];
                            }
                            echo '<td data-label="'.$label.'">';
                                echo $td['c'];
                            echo '</td>';
                            ++$index;
                        }
                    echo '</tr>';
                }
            echo '</tbody>';
        echo '</table>';
    }
echo '</div>';