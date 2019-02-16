<?php
/**
 * Here's some code to make a Google Analytics non-Javascript call
 * 
 * You are permitted to use, copy, modify, and distribute the code and its
 * documentation, with or without modification, for any purpose, provided the
 * following:
 * 
 * Indemnity
 * You agree to indemnify and hold harmless the authors of this software and
 * any contributors for any direct, indirect, incidental, or consequential
 * third-party claims, actions or suits, as well as any related expenses,
 * liabilities, damages, settlements or fees arising from your use or misuse
 * of the Software, or a violation of any terms of this license.
 * 
 * Disclaimer of Warranty
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESSED
 * OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY,
 * PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR
 * PURPOSE.
 * 
 * Limitations of Liability
 * YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE
 * SOFTWARE. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE
 * SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT
 * OF, OR IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY
 * RESPONSIBLE FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS
 * ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM
 * ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR
 * UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.
 **/

$utCookie = rand(10000000,99999999);      // random cookie number
$utRandom = rand(1000000000,2147483647);  // number under 2147483647
$utToday = time();                        // today
$usrVars = '-';                           // user variables you want to track

$cookieUTMZ = array(
  'utmccn' => '(direct)',
  'utmcsr' => '(direct)',
  'utmcmd' => '(none)'
);

foreach($cookieUTMZ as $k => $v) { $cookieUTMZstr[] = urlencode($k.'=').$v; }
$cookieUTMZstr = implode(urlencode('|'), $cookieUTMZstr);

$cookieSettings = array(
  '__utma' => $utCookie.'.'.$utRandom.'.'.$utToday.'.'.$utToday.'.'.$utToday.'.2;',
  
  '__utmb' => $utCookie.';',
  '__utmc' => $utCookie.';',
  '__utmz' => $utCookie.'.'.$utToday.'2.2'.$cookieUTMZstr.urlencode(';'),
  '__utmv' => $utCookie.'.'.$usrVars.';'
);

foreach($cookieSettings as $k => $v)
{
  $cookieURIstr[] = (strcmp($k,'__utmz') == 0) ?  urlencode($k.'=').$v :
                                                  urlencode($k.'='.$v);
}
$cookieURIstr = implode(urlencode('+'), $cookieURIstr);

$charset = 'utf-8';
$pageURI = 'http://u.myzaker.com/share/index?debug=1';
$referrer = '';

$params = array(
  'utmwv' => 1,                           // Urchin/Analytics <version></version>
  'utmac' => 'UA-5219907-1',               // your Google Analytics account
  'utmhn' => 'wasa.sinaapp.com',        // our domain
  'utmn' => rand(1000000000,9999999999),  // random request number
  'utmsr' => '-',                         // screen resolution
  'utmcs' => $charset,                    // document encoding
  'utmul' => '-',                         // user language
  'utmje' => 0,                           // java enabled or not
  'utmfl' => '-',                         // user's flash version
  'utmdt' => 'Wasabi Test title',            // document title
  'utmr'  => $referrer,                   // referrer URL
  'utmp'  => $pageURI,                    // document page URI
  'utmcc' => $cookieURIstr                // cookie string
);

foreach($params as $k => $v)
{
  $paramsURI[] =
    (strcmp($k,'utmcc') == 0) ? urlencode($k).'='.$v : 
                                urlencode($k).'='.urlencode($v);
}
$paramsURI = implode('&',$paramsURI);
  
$urchinURL = 'http://www.google-analytics.com/__utm.gif?'.$paramsURI;

echo "urchinURL  ==  $urchinURL <br>";


/* if your provider doesn't allow fopen with urls, use the following */

$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $urchinURL);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 8); // or whatever you want
$file_contents = curl_exec($ch);
curl_close($ch);

/* otherwise, you can just do this */

$handle = fopen($urchinURL, "r");
$test = fgets($handle);
fclose($handle);

?>