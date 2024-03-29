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

    abstract class BaseModel
    {
        protected $page;
        protected $action;
        protected $ajax;
        protected $time;

        function __construct()
        {
            // this is necessary because if HTTP_HOST doesn't have the PORT the parse_url is null
            $current_host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);
            if( $current_host === null ) {
                $current_host = $_SERVER['HTTP_HOST'];
            }

            if( parse_url(osc_base_url(), PHP_URL_HOST) !== $current_host ) {
                // first check if it's http or https
                $url = 'http://';
                if(osc_is_ssl()) {
                    $url = 'https://';
                }
                // append the domain
                $url .= parse_url(osc_base_url(), PHP_URL_HOST);
                // append the port number if it's necessary
                $http_port = parse_url($_SERVER['HTTP_HOST'], PHP_URL_PORT);
                if( $http_port !== 80 ) {
                    $url .= ':' . parse_url($_SERVER['HTTP_HOST'], PHP_URL_PORT);
                }
                // append the request
                $url .= $_SERVER['REQUEST_URI'];
                $this->redirectTo($url);
            }

            $this->subdomain_params($current_host);
            $this->page   = Params::getParam('page');
            $this->action = Params::getParam('action');
            $this->ajax   = false;
            $this->time   = list($sm, $ss) = explode(' ', microtime());
            WebThemes::newInstance();
            osc_run_hook( 'init' );
        }

        function __destruct()
        {
            if( !$this->ajax && OSC_DEBUG ) {
                echo '<!-- ' . $this->getTime() . ' seg. -->';
            }
        }

        //to export variables at the business layer
        function _exportVariableToView($key, $value)
        {
            View::newInstance()->_exportVariableToView($key, $value);
        }

        //only for debug (deprecated, all inside View.php)
        function _view($key = null)
        {
            View::newInstance()->_view($key);
        }

        //Funciones que se tendran que reescribir en la clase que extienda de esta
        protected abstract function doModel();
        protected abstract function doView($file);

        function do400()
        {
            Rewrite::newInstance()->set_location('error');
            header('HTTP/1.1 400 Bad Request');
            osc_current_web_theme_path('404.php');
            exit;
        }

        function do404()
        {
            Rewrite::newInstance()->set_location('error');
            header('HTTP/1.1 404 Not Found');
            osc_current_web_theme_path('404.php');
            exit;
        }

        function do410()
        {
            Rewrite::newInstance()->set_location('error');
            header('HTTP/1.1 410 Gone');
            osc_current_web_theme_path('404.php');
            exit;
        }

        function redirectTo($url, $code = null)
        {
            osc_redirect_to($url, $code);
        }

        function getTime()
        {
            $timeEnd = list($em, $es) = explode(' ', microtime());
            return ($timeEnd[0] + $timeEnd[1]) - ($this->time[0] + $this->time[1]);
        }

        private function subdomain_params($host) {
            $subdomain_type = osc_subdomain_type();
            $subhost = osc_subdomain_host();
            // strpos is used to check if the domain is different, useful when accessing the website by diferent domains
            if($subdomain_type!='' && $subhost!='' && strpos($host, $subhost)!==false) {
                if(preg_match('|^(www\.)?(.+)\.'.$subhost.'$|i', $host, $match)) {
                    $subdomain = $match[2];
                    if($subdomain!='' && $subdomain!='www') {
                        if($subdomain_type=='category') {
                            $category = Category::newInstance()->findBySlug($subdomain);
                            if(isset($category['pk_i_id'])) {
                                View::newInstance()->_exportVariableToView('subdomain_name', $category['s_name']);
                                View::newInstance()->_exportVariableToView('subdomain_slug', $category['s_slug']);
                                Params::setParam('sCategory', $category['pk_i_id']);
                                if(Params::getParam('page')=='') {
                                    Params::setParam('page', 'search');
                                }
                            } else {
                                $this->do400();
                            }
                        } else if($subdomain_type=='country') {
                            $country = Country::newInstance()->findBySlug($subdomain);
                            if(isset($country['pk_c_code'])) {
                                View::newInstance()->_exportVariableToView('subdomain_name', $country['s_name']);
                                View::newInstance()->_exportVariableToView('subdomain_slug', $country['s_slug']);
                                Params::setParam('sCountry', $country['pk_c_code']);
                            } else {
                                $this->do400();
                            }
                        } else if($subdomain_type=='region') {
                            $region = Region::newInstance()->findBySlug($subdomain);
                            if(isset($region['pk_i_id'])) {
                                View::newInstance()->_exportVariableToView('subdomain_name', $region['s_name']);
                                View::newInstance()->_exportVariableToView('subdomain_slug', $region['s_slug']);
                                Params::setParam('sRegion', $region['pk_i_id']);
                            } else {
                                $this->do400();
                            }
                        } else if($subdomain_type=='city') {
                            $city = City::newInstance()->findBySlug($subdomain);
                            if(isset($city['pk_i_id'])) {
                                View::newInstance()->_exportVariableToView('subdomain_name', $city['s_name']);
                                View::newInstance()->_exportVariableToView('subdomain_slug', $city['s_slug']);
                                Params::setParam('sCity', $city['pk_i_id']);
                            } else {
                                $this->do400();
                            }
                        } else {
                            $this->do400();
                        }
                    }
                }
            }
        }
    }

    /* file end: ./dq-includes/osclass/core/BaseModel.php */
?>