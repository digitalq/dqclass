<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    $d_now = date('Y-m-d H:i:s');
    $i_now = strtotime($d_now);

    // Hourly crons
    $cron = Cron::newInstance()->getCronByType('HOURLY');
    if( is_array($cron) ) {
        $i_next = strtotime($cron['d_next_exec']);

        if( (CLI && (Params::getParam('cron-type') === 'hourly')) || ((($i_now - $i_next) >= 0) && !CLI) ) {
            // update the next execution time in t_cron
            $d_next = date('Y-m-d H:i:s', $i_now + 3600);
            Cron::newInstance()->update(array('d_last_exec' => $d_now, 'd_next_exec' => $d_next),
                                        array('e_type'      => 'HOURLY'));

            // Run cron AFTER updating the next execution time to avoid double run of cron
            $purge = osc_purge_latest_searches();
            if( $purge == 'hour' ) {
                LatestSearches::newInstance()->purgeDate( date('Y-m-d H:i:s', ( time() - 3600) ) );
            } else if( !in_array($purge, array('forever', 'day', 'week')) ) {
                LatestSearches::newInstance()->purgeNumber($purge);
            }
            osc_update_location_stats(true, 'auto');

            // WARN EXPIRATION EACH HOUR (COMMENT TO DISABLE)
            // NOTE: IF THIS IS ENABLE, SAME CODE SHOULD BE DISABLE ON CRON DAILY
            if(is_numeric(osc_warn_expiration()) && osc_warn_expiration()>0) {
                $items = Item::newInstance()->findByHourExpiration(24*osc_warn_expiration());
                foreach($items as $item) {
                    osc_run_hook('hook_email_warn_expiration', $item);
                }
            }

            $files = glob(osc_content_path().'uploads/temp/qqfile_*');
            foreach($files as $file) {
                if((time()-filectime($file))>(2*3600)) {
                    @unlink($file);
                }
            }

            osc_run_hook('cron_hourly');
        }
    }

    // Daily crons
    $cron = Cron::newInstance()->getCronByType('DAILY');
    if( is_array($cron) ) {
        $i_next = strtotime($cron['d_next_exec']);

        if( (CLI && (Params::getParam('cron-type') === 'daily')) || ((($i_now - $i_next) >= 0) && !CLI) ) {
            // update the next execution time in t_cron
            $d_next = date('Y-m-d H:i:s', $i_now + (24 * 3600));
            Cron::newInstance()->update(array('d_last_exec' => $d_now, 'd_next_exec' => $d_next),
                array('e_type'      => 'DAILY'));


            osc_do_auto_upgrade();

            osc_runAlert('DAILY', $cron['d_last_exec']);

            // Run cron AFTER updating the next execution time to avoid double run of cron
            $purge = osc_purge_latest_searches();
            if( $purge == 'day' ) {
                LatestSearches::newInstance()->purgeDate( date('Y-m-d H:i:s', ( time() - (24 * 3600) ) ) );
            }
            osc_update_cat_stats();

            // WARN EXPIRATION EACH DAY (UNCOMMENT TO ENABLE)
            // NOTE: IF THIS IS ENABLE, SAME CODE SHOULD BE DISABLE ON CRON HOURLY
            /*if(is_numeric(osc_warn_expiration()) && osc_warn_expiration()>0) {
                $items = Item::newInstance()->findByDayExpiration(osc_warn_expiration());
                foreach($items as $item) {
                    osc_run_hook('hook_email_warn_expiration', $item);
                }
            }*/

            osc_run_hook('cron_daily');
        }
    }

    // Weekly crons
    $cron = Cron::newInstance()->getCronByType('WEEKLY');
    if(is_array($cron)) {
        $i_next = strtotime($cron['d_next_exec']);

        if( (CLI && (Params::getParam('cron-type') === 'weekly')) || ((($i_now - $i_next) >= 0) && !CLI) ) {
            // update the next execution time in t_cron
            $d_next = date('Y-m-d H:i:s', $i_now + (7 * 24 * 3600));
            Cron::newInstance()->update(array('d_last_exec' => $d_now, 'd_next_exec' => $d_next),
                                        array('e_type'      => 'WEEKLY'));

            // Run cron AFTER updating the next execution time to avoid double run of cron
            $purge = osc_purge_latest_searches();
            if( $purge == 'week' ) {
                LatestSearches::newInstance()->purgeDate( date('Y-m-d H:i:s', ( time() - (7 * 24 * 3600) ) ) );
            }
            osc_run_hook('cron_weekly');
        }
    }

    osc_run_hook('cron');
    /* file end: ./dq-includes/osclass/cron.php */