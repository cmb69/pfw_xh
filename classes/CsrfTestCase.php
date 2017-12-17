<?php

/*
 * Copyright 2013-2014 The CMSimple_XH developers
 * Copyright 2014-2017 Christoph M. Becker
 *
 * This file is part of Pfw_XH.
 *
 * Pfw_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pfw_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pfw_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pfw;

use PHPUnit\Framework\TestCase;

/**
 * Testing CSRF protection.
 * 
 * Protection against CSRF attacks is very important for web applications in
 * general.  In particular, each administration action that changes the state of
 * the server has to be protected against CSRF attacks.  CMSimple_XH already
 * offers an easy means to implement CSRF protection, see
 * http://dev-doc.cmsimple-xh.org/md_tutorials__x_h__c_s_r_f_protection.html.
 * This class can be extended to actually test the CSRF protection as
 * integration test (opposed to a unit test).  The CsrfTestCase uses cURL to
 * first log in as administrator (using the default password), and then triggers
 * additional cURL requests which lack the CSRF token, and as such should be
 * responded with a 403 Forbidden status code.  Otherwise the test fails.  The
 * query string and payload of each test has to be returned by dataForAttack().
 *
 * @note The environment variable `CMSIMPLE_URL` has to be set to the fully
 *       qualified URL of the CMSimple_XH installation.
 */
abstract class CsrfTestCase extends TestCase
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var resource
     */
    private $curlHandle;

    /**
     * @var string
     */
    private $cookieFile;

    /**
     * Sets up the CSRF test.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->url = getenv('CMSIMPLE_URL');
        $this->cookieFile = tempnam(sys_get_temp_dir(), 'CC');

        $this->curlHandle = curl_init($this->url . '?&login=true&keycut=test');
        curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, $this->cookieFile);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_exec($this->curlHandle);
        curl_close($this->curlHandle);
    }

    /**
     * @param array $fields
     * @return void
     */
    private function setCurlOptions($fields)
    {
        $options = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE => $this->cookieFile,
        );
        curl_setopt_array($this->curlHandle, $options);
    }

    /**
     * Tests whether the attack returns a 403 response code.
     *
     * @param array  $fields
     * @param string $queryString
     * @return void
     * @dataProvider dataForAttack
     */
    public function testAttack($fields, $queryString = null)
    {
        $url = $this->url . (isset($queryString) ? '?' . $queryString : '');
        $this->curlHandle = curl_init($url);
        $this->setCurlOptions($fields);
        curl_exec($this->curlHandle);
        $actual = curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);
        curl_close($this->curlHandle);
        $this->assertEquals(403, $actual);
    }

    /**
     * Provides the test data for testAttack().
     *
     * The method has to return an array of arrays, where each inner array
     * contains the data for a single attack test, which is an array of POST
     * data and the query string. For instance, testing whether saving the site
     * structure in Pagemanager_XH is protected against CSRF, the method body
     * would be:
     * 
     *      return array(
     *          array(
     *              array( // POST data
     *                    'admin' => 'plugin_main',
     *                    'action' => 'plugin_save'
     *              ),
     *              '&pagemanager' // query string
     *          )
     *      );
     *
     * @return array
     */
    abstract public function dataForAttack();
}
