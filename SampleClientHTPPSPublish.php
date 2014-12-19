<?php
/*
 * Copyright (c) 2014, WSO2 Inc. (http://www.wso2.org) All Rights Reserved.
 *
 * WSO2 Inc. licenses this file to you under the Apache License,
 * Version 2.0 (the "License"); you may not use this file except
 * in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
error_reporting(E_ALL);
include_once __DIR__ . '/vendor/autoload.php';


use org\wso2\carbon\databridge\commons\thrift\service\general\ThriftEventTransmissionServiceClient;
use publisher\Publisher;
use publisher\Event;
use publisher\PublisherConfiguration;
use publisher\PublisherConstants;
use publisher\StreamDefinitionException;
use publisher\NullPointerException;




$receiverURL = 'https://10.100.5.198:9443';
$username = 'admin';
$password = 'admin';

try {

    
    //Initializing a Publisher object
    $publisher = new Publisher($receiverURL, $username, $password);


    //JSON formatted stream definition
	$streamDefinition = "{ 'name':'demo', "
			             ."'version':'1.0.0', "
			             ."'nickName':'Demo Stream Definition'," 
			             ."'description':'This is a description',"    
			             ."'metaData':[{'name':'metaField1','type':'STRING'}],"
			             ."'correlationData':[{'name':'corrField1','type':'STRING'}],"
					     ."'payloadData':[ {'name':'payloadField1','type':'STRING'},"
					                       ."{'name':'payloadField2','type':'DOUBLE'},"
					                         ."{'name':'payloadField3','type':'STRING'},"
							                 ."{'name':'payloadField4','type':'INT'} ] }";	
    
	//Adding the strem definition to BAM
    $streamId = $publisher->defineStream($streamDefinition);
    echo $streamId.PHP_EOL;

    //Searching a stream definition
    $streamId =  $publisher->findStreamId( "demo", "1.0.0" );
    
    //Initializing an Event object
    $event = new Event($streamId, time());
     
    //Setting up event attributes. The of each array should follow the data type and order of the stream definiiton
    $metaData = ['meta1'];
    $correlationData = ['corr1'];
    $payloadData = ['pay1',pi(),'pay2',888];
    $arbitraryDataMap = ['x'=>'arb1','y'=>'arb2'];

    //Adding the attributes to the Event object
    $event->setMetaData($metaData);
    $event->setCorrelationData($correlationData);
    $event->setPayloadData($payloadData);
    $event->setArbitraryDataMap($arbitraryDataMap);

    //Publish the event to BAM
    $publisher->publish($event);
     

}catch(Exception $e){
    var_dump($e);
}

