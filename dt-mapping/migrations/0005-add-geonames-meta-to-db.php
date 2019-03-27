<?php

/**
 * Class DT_Mapping_Module_Migration_0005
 *
 * @note    Add a custom table for the site to hold geonames metadata, like custom names/translations and populations
 *
 */
class DT_Mapping_Module_Migration_0005 extends DT_Mapping_Module_Migration {

    /**
     * @throws \Exception  Got error when creating table $name.
     */
    public function up() {
        /**
         * Install tables
         */
        global $wpdb;
        $expected_tables = $this->get_expected_tables();
        foreach ( $expected_tables as $name => $table) {
            $rv = $wpdb->query( $table ); // WPCS: unprepared SQL OK
            if ( $rv == false ) {
                dt_write_log( "Got error when creating table $name: $wpdb->last_error" );
            }
        }
    }

    /**
     * @throws \Exception  Got error when dropping table $name.
     */
    public function down() {
        global $wpdb;
        $expected_tables = $this->get_expected_tables();
        foreach ( $expected_tables as $name => $table) {
            $rv = $wpdb->query( "DROP TABLE `{$name}`" ); // WPCS: unprepared SQL OK
            if ( $rv == false ) {
                throw new Exception( "Got error when dropping table $name: $wpdb->last_error" );
            }
        }
    }

    /**
     * @return array
     */
    public function get_expected_tables(): array {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        return array(
            "{$wpdb->prefix}dt_geonames_meta" =>
                "CREATE TABLE  IF NOT EXISTS `{$wpdb->prefix}dt_geonames_meta` (
                  `id` BIGINT(22) unsigned NOT NULL AUTO_INCREMENT,
                  `geonameid` BIGINT(22) unsigned NOT NULL,
                  `meta_key` VARCHAR(50) DEFAULT NULL,
                  `meta_value` LONGTEXT,
                  PRIMARY KEY (`id`),
                  KEY `geonameid` (`geonameid`),
                  KEY `meta_key` (`meta_key`)
                ) $charset_collate;",
        );
    }

    /**
     * Test function
     */
    public function test() {
    }
}
