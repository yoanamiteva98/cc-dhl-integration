<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DHLController extends Controller
{
    public function createWaybill(Request $request)
    {
        $username = 'cloudcartBG'; 
        $password = 'H^2rH#9lR$3iE^6y'; 
    
        $requestData = $request->all();
        $requestBody = self::createRequestBody($request);
        
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'Message-Reference' => 'd0e7832e-5c98-11ea-bc55-0242ac13', 
            'Authorization' => 'Basic ' . base64_encode($username . ':' . $password) 
        ])->post('https://express.api.dhl.com/mydhlapi/test/shipments', $requestBody);
        
        $documents = $response->json()['documents'] ?? false;
        $invoiceDocument = false;
        if($documents) {
            foreach ($documents as $document) {
                if ($document['typeCode'] === 'invoice') {
                    $invoiceDocument = $document;
                    break;
                }
            }
        }
        
        if ($invoiceDocument) {
            $invoiceDocument['content'] = base64_decode($invoiceDocument['content']);
        } 

        return response()->json($response->json());
    }

    public function createRequestBody($requestData) {
        $requestBody = [
            'plannedShippingDateAndTime' => '2024-03-15T19:19:40 GMT+00:00',
            'pickup' => [
                'isRequested' => false
            ],
            'productCode' => 'N',
            'localProductCode' => 'N',
            'getRateEstimates' => false,
            'accounts' => [
                [
                    'typeCode' => 'shipper',
                    'number' => '316132891'
                ]
            ],
            'valueAddedServices' => [
                [
                    'serviceCode' => 'II',
                    'value' => 0,
                    'currency' => 'BGN'
                ]
            ],
            'outputImageProperties' => [
                'printerDPI' => 300,
                'encodingFormat' => 'pdf',
                'imageOptions' => [
                    [
                        'typeCode' => 'invoice',
                        'templateName' => 'COMMERCIAL_INVOICE_P_10',
                        'isRequested' => true,
                        'invoiceType' => 'commercial',
                        'languageCode' => 'eng',
                        'languageCountryCode' => 'BG'
                    ],
                    [
                        'typeCode' => 'waybillDoc',
                        'templateName' => 'ARCH_8x4',
                        'isRequested' => true,
                        'hideAccountNumber' => false,
                        'numberOfCopies' => 1
                    ],
                    [
                        'typeCode' => 'label',
                        'templateName' => 'ECOM26_84_001',
                        'renderDHLLogo' => true,
                        'fitLabelsToA4' => false
                    ]
                ],
                'splitTransportAndWaybillDocLabels' => true,
                'allDocumentsInOneImage' => false,
                'splitDocumentsByPages' => false,
                'splitInvoiceAndReceipt' => true,
                'receiptAndLabelsInOneImage' => false
            ],
            'customerDetails' => [
                'shipperDetails' => [
                    'postalAddress' => [
                        'postalCode' => $requestData['addressSender']['postalCode'],
                        'cityName' => $requestData['addressSender']['cityName'],
                        'countryCode' => $requestData['addressSender']['countryCode'],
                        'addressLine1' => $requestData['addressSender']['addressLine1'],
                        'addressLine2' => $requestData['addressSender']['addressLine2'],
                        'addressLine3' => $requestData['addressSender']['addressLine3'],
                        'countyName' => $requestData['addressSender']['countyName'],
                        'countryName' => $requestData['addressSender']['countryName']
                    ],
                    'contactInformation' => [
                        'email' => $requestData['addressSender']['email'],
                        'phone' => $requestData['addressSender']['phone'],
                        'mobilePhone' => '18211309039',
                        'companyName' => $requestData['addressSender']['companyName'],
                        'fullName' => $requestData['addressSender']['fullName']
                    ],
                    'registrationNumbers' => [
                        [
                            'typeCode' => 'SDT',
                            'number' => $requestData['addressSender']['taxId'],
                            'issuerCountryCode' => 'BG'
                        ]
                    ],
                    'bankDetails' => [
                        [
                            'name' => 'CCB',
                            'settlementLocalCurrency' => 'BGN',
                            'settlementForeignCurrency' => 'BGN'
                        ]
                    ],
                    'typeCode' => 'business'
                ],
                'receiverDetails' => [
                    'postalAddress' => [
                        'cityName' => $requestData['addressReceiver']['cityName'],
                        'countryCode' => $requestData['addressReceiver']['countryCode'],
                        'postalCode' => $requestData['addressReceiver']['postalCode'],
                        'addressLine1' => $requestData['addressReceiver']['addressLine1'],
                        'countryName' => $requestData['addressReceiver']['countryName']
                    ],
                    'contactInformation' => [
                        'email' => $requestData['addressReceiver']['email'],
                        'phone' => $requestData['addressReceiver']['phone'],
                        'mobilePhone' => '9402825666',
                        'companyName' => $requestData['addressReceiver']['companyName'],
                        'fullName' => $requestData['addressReceiver']['fullName']
                    ],
                    'registrationNumbers' => [
                        [
                            'typeCode' => 'SSN',
                            'number' => '123',
                            'issuerCountryCode' => 'BG'
                        ]
                    ],
                    'bankDetails' => [
                        [
                            'name' => 'OBB',
                            'settlementLocalCurrency' => 'BGN',
                            'settlementForeignCurrency' => 'BGN'
                        ]
                    ],
                    'typeCode' => 'business'
                ]
            ],
            'content' => [
                'packages' => [
                    [
                        'typeCode' => '2BP',
                        'weight' => floatval($requestData['packageData']['weight']),
                        'dimensions' => [
                            'length' => 1,
                            'width' => 1,
                            'height' => 1
                        ],
                        'customerReferences' => [
                            [
                                'value' => '3654673',
                                'typeCode' => 'CU'
                            ]
                        ],
                        'description' => $requestData['packageData']['packageDescription'],
                        'labelDescription' => $requestData['packageData']['labelDescription']
                    ]
                ],
                'isCustomsDeclarable' => false,
                'declaredValue' => 2000,
                'declaredValueCurrency' => 'BGN',
                'exportDeclaration' => [
                    'lineItems' => [
                        [
                            'number' => 1,
                            'description' => $requestData['packageData']['itemName'],
                            'price' => floatval($requestData['packageData']['price']),
                            'quantity' => [
                                'value' => 1,
                                'unitOfMeasurement' => 'GM'
                            ],
                            'commodityCodes' => [
                                [
                                    'typeCode' => 'outbound',
                                    'value' => '84713000'
                                ],
                                [
                                    'typeCode' => 'inbound',
                                    'value' => '5109101110'
                                ]
                            ],
                            'exportReasonType' => 'permanent',
                            'manufacturerCountry' => 'BG',
                            'exportControlClassificationNumber' => 'US123456789',
                            'weight' => [
                                'netValue' => floatval($requestData['packageData']['weight']),
                                'grossValue' => floatval($requestData['packageData']['weight']) + 1
                            ],
                            'isTaxesPaid' => true,
                            'additionalInformation' => [
                                $requestData['packageData']['additionalInformation'] ?? 'No additional information',
                            ],
                            'customerReferences' => [
                                [
                                    'typeCode' => 'AFE',
                                    'value' => '1299210'
                                ]
                            ],
                            'customsDocuments' => [
                                [
                                    'typeCode' => 'COO',
                                    'value' => 'MyDHLAPI - LN#1-CUSDOC-001'
                                ]
                            ]
                        ],
                    ],
                    'invoice' => [
                        'number' => '2667168671',
                        'date' => '2024-03-06',
                        'instructions' => [
                            $requestData['packageData']['additionalDeliveryMessage'] ?? 'No additional information'
                        ],
                        'totalNetWeight' => floatval($requestData['packageData']['weight']),
                        'totalGrossWeight' => floatval($requestData['packageData']['weight']) + 1,
                        'customerReferences' => [
                            [
                                'typeCode' => 'UCN',
                                'value' => 'UCN-783974937'
                            ],
                            [
                                'typeCode' => 'CN',
                                'value' => 'CUN-76498376498'
                            ],
                            [
                                'typeCode' => 'RMA',
                                'value' => 'MyDHLAPI-TESTREF-001'
                            ]
                        ],
                        'termsOfPayment' => '100 days',
                        'indicativeCustomsValues' => [
                            'importCustomsDutyValue' => 0,
                            'importTaxesValue' => 0
                        ]
                    ],
                    'remarks' => [
                        [
                            'value' => 'Right side up only'
                        ]
                    ],
                    'additionalCharges' => [
                        [
                            'value' => 0.15,
                            'caption' => 'ins charges',
                            'typeCode' => 'insurance'
                        ],
                    ],
                    'destinationPortName' => 'Sofia',
                    'placeOfIncoterm' => 'Varna',
                    'payerVATNumber' => '12345ED',
                    'recipientReference' => '01291344',
                    'exporter' => [
                        'id' => '121233',
                        'code' => 'S'
                    ],
                    'packageMarks' => '',
                    'declarationNotes' => [
                        [
                            'value' => 'up to three declaration notes'
                        ]
                    ],
                    'exportReference' => 'export reference',
                    'exportReason' => 'export reason',
                    'exportReasonType' => 'permanent',
                    'licenses' => [
                        [
                            'typeCode' => 'export',
                            'value' => '123127233'
                        ]
                    ],
                    'shipmentType' => 'personal',
                    'customsDocuments' => [
                        [
                            'typeCode' => 'INV',
                            'value' => 'MyDHLAPI - CUSDOC-001'
                        ]
                    ]
                ],
                'description' => 'Shipment',
                'USFilingTypeValue' => '12345',
                'incoterm' => 'DAP',
                'unitOfMeasurement' => 'metric'
            ],
            'shipmentNotification' => [
                [
                    'typeCode' => 'email',
                    'receiverId' => 'shipmentnotification@mydhlapisample.com',
                    'languageCode' => 'eng',
                    'languageCountryCode' => 'UK',
                    'bespokeMessage' => 'message to be included in the notification'
                ]
            ],
            'getTransliteratedResponse' => false,
            'estimatedDeliveryDate' => [
                'isRequested' => true,
                'typeCode' => 'QDDC'
            ],
            'getAdditionalInformation' => [
                [
                    'typeCode' => 'pickupDetails',
                    'isRequested' => true
                ]
            ]
        ];

        return $requestBody;
    }
    
}

// $username = 'cloudcartBG'; 
        // $password = 'H^2rH#9lR$3iE^6y'; 
    
        // $bodyJson = '{
        //     "plannedShippingDateAndTime": "2024-03-13T19:19:40 GMT+00:00",
        //     "pickup": {
        //       "isRequested": false
        //     },
        //     "productCode": "N",
        //     "localProductCode": "N",
        //     "getRateEstimates": false,
        //     "accounts": [
        //       {
        //         "typeCode": "shipper",
        //         "number": "316132891"
        //       }
        //     ],
        //     "valueAddedServices": [
        //       {
        //         "serviceCode": "II",
        //         "value": 10,
        //         "currency": "BGN"
        //       }
        //     ],
        //     "outputImageProperties": {
        //       "printerDPI": 300,
        //       "encodingFormat": "pdf",
        //       "imageOptions": [
        //         {
        //           "typeCode": "invoice",
        //           "templateName": "COMMERCIAL_INVOICE_P_10",
        //           "isRequested": true,
        //           "invoiceType": "commercial",
        //           "languageCode": "eng",
        //           "languageCountryCode": "BG"
        //         },
        //         {
        //           "typeCode": "waybillDoc",
        //           "templateName": "ARCH_8x4",
        //           "isRequested": true,
        //           "hideAccountNumber": false,
        //           "numberOfCopies": 1
        //         },
        //         {
        //           "typeCode": "label",
        //           "templateName": "ECOM26_84_001",
        //           "renderDHLLogo": true,
        //           "fitLabelsToA4": false
        //         }
        //       ],
        //       "splitTransportAndWaybillDocLabels": true,
        //       "allDocumentsInOneImage": false,
        //       "splitDocumentsByPages": false,
        //       "splitInvoiceAndReceipt": true,
        //       "receiptAndLabelsInOneImage": false
        //     },
        //     "customerDetails": {
        //       "shipperDetails": {
        //         "postalAddress": {
        //           "postalCode": "9000",
        //           "cityName": "Varna",
        //           "countryCode": "BG",
        //           "addressLine1": "Andrey Saharov 19",
        //           "addressLine2": "vhod 2",
        //           "addressLine3": "et. 6",
        //           "countyName": "Varna",
        //           "countryName": "Bulgaria"
        //         },
        //         "contactInformation": {
        //           "email": "shipper_create_shipmentapi@dhltestmail.com",
        //           "phone": "18211309039",
        //           "mobilePhone": "18211309039",
        //           "companyName": "Cider BookStore",
        //           "fullName": "LiuWeiMing"
        //         },
        //         "registrationNumbers": [
        //           {
        //             "typeCode": "SDT",
        //             "number": "CN123456789",
        //             "issuerCountryCode": "BG"
        //           }
        //         ],
        //         "bankDetails": [
        //           {
        //             "name": "CCB",
        //             "settlementLocalCurrency": "BGN",
        //             "settlementForeignCurrency": "BGN"
        //           }
        //         ],
        //         "typeCode": "business"
        //       },
        //       "receiverDetails": {
        //         "postalAddress": {
        //           "cityName": "Sofia",
        //           "countryCode": "BG",
        //           "postalCode": "1000",
        //           "addressLine1": "Panorama 5",
        //           "countryName": "Bulgaria"
        //         },
        //         "contactInformation": {
        //           "email": "recipient_create_shipmentapi@dhltestmail.com",
        //           "phone": "9402825665",
        //           "mobilePhone": "9402825666",
        //           "companyName": "Cloudcart",
        //           "fullName": "Peter Iliev"
        //         },
        //         "registrationNumbers": [
        //           {
        //             "typeCode": "SSN",
        //             "number": "US123456789",
        //             "issuerCountryCode": "BG"
        //           }
        //         ],
        //         "bankDetails": [
        //           {
        //             "name": "OBB",
        //             "settlementLocalCurrency": "BGN",
        //             "settlementForeignCurrency": "BGN"
        //           }
        //         ],
        //         "typeCode": "business"
        //       }
        //     },
        //     "content": {
        //       "packages": [
        //         {
        //           "typeCode": "2BP",
        //           "weight": 0.5,
        //           "dimensions": {
        //             "length": 1,
        //             "width": 1,
        //             "height": 1
        //           },
        //           "customerReferences": [
        //             {
        //               "value": "3654673",
        //               "typeCode": "CU"
        //             }
        //           ],
        //           "description": "Piece content description",
        //           "labelDescription": "bespoke label description"
        //         }
        //       ],
        //       "isCustomsDeclarable": false,
        //       "declaredValue": 120,
        //       "declaredValueCurrency": "BGN",
        //       "exportDeclaration": {
        //         "lineItems": [
        //           {
        //             "number": 1,
        //             "description": "Harry Steward biography first edition",
        //             "price": 15,
        //             "quantity": {
        //               "value": 4,
        //               "unitOfMeasurement": "GM"
        //             },
        //             "commodityCodes": [
        //               {
        //                 "typeCode": "outbound",
        //                 "value": "84713000"
        //               },
        //               {
        //                 "typeCode": "inbound",
        //                 "value": "5109101110"
        //               }
        //             ],
        //             "exportReasonType": "permanent",
        //             "manufacturerCountry": "BG",
        //             "exportControlClassificationNumber": "US123456789",
        //             "weight": {
        //               "netValue": 0.1,
        //               "grossValue": 0.7
        //             },
        //             "isTaxesPaid": true,
        //             "additionalInformation": [
        //               "450pages"
        //             ],
        //             "customerReferences": [
        //               {
        //                 "typeCode": "AFE",
        //                 "value": "1299210"
        //               }
        //             ],
        //             "customsDocuments": [
        //               {
        //                 "typeCode": "COO",
        //                 "value": "MyDHLAPI - LN#1-CUSDOC-001"
        //               }
        //             ]
        //           },
        //           {
        //             "number": 2,
        //             "description": "Andromeda Chapter 394 - Revenge of Brook",
        //             "price": 15,
        //             "quantity": {
        //               "value": 4,
        //               "unitOfMeasurement": "GM"
        //             },
        //             "commodityCodes": [
        //               {
        //                 "typeCode": "outbound",
        //                 "value": "6109100011"
        //               },
        //               {
        //                 "typeCode": "inbound",
        //                 "value": "5109101111"
        //               }
        //             ],
        //             "exportReasonType": "permanent",
        //             "manufacturerCountry": "BG",
        //             "exportControlClassificationNumber": "",
        //             "weight": {
        //               "netValue": 0.1,
        //               "grossValue": 0.7
        //             },
        //             "isTaxesPaid": true,
        //             "additionalInformation": [
        //               "36pages"
        //             ],
        //             "customerReferences": [
        //               {
        //                 "typeCode": "AFE",
        //                 "value": "1299211"
        //               }
        //             ],
        //             "customsDocuments": [
        //               {
        //                 "typeCode": "COO",
        //                 "value": "MyDHLAPI - LN#1-CUSDOC-001"
        //               }
        //             ]
        //           }
        //         ],
        //         "invoice": {
        //           "number": "2667168671",
        //           "date": "2022-10-22",
        //           "instructions": [
        //             "Handle with care"
        //           ],
        //           "totalNetWeight": 0.4,
        //           "totalGrossWeight": 0.5,
        //           "customerReferences": [
        //             {
        //               "typeCode": "UCN",
        //               "value": "UCN-783974937"
        //             },
        //             {
        //               "typeCode": "CN",
        //               "value": "CUN-76498376498"
        //             },
        //             {
        //               "typeCode": "RMA",
        //               "value": "MyDHLAPI-TESTREF-001"
        //             }
        //           ],
        //           "termsOfPayment": "100 days",
        //           "indicativeCustomsValues": {
        //             "importCustomsDutyValue": 150.57,
        //             "importTaxesValue": 49.43
        //           }
        //         },
        //         "remarks": [
        //           {
        //             "value": "Right side up only"
        //           }
        //         ],
        //         "additionalCharges": [
        //           {
        //             "value": 10,
        //             "caption": "fee",
        //             "typeCode": "freight"
        //           },
        //           {
        //             "value": 20,
        //             "caption": "freight charges",
        //             "typeCode": "other"
        //           },
        //           {
        //             "value": 10,
        //             "caption": "ins charges",
        //             "typeCode": "insurance"
        //           },
        //           {
        //             "value": 7,
        //             "caption": "rev charges",
        //             "typeCode": "reverse_charge"
        //           }
        //         ],
        //         "destinationPortName": "Sofia",
        //         "placeOfIncoterm": "Varna",
        //         "payerVATNumber": "12345ED",
        //         "recipientReference": "01291344",
        //         "exporter": {
        //           "id": "121233",
        //           "code": "S"
        //         },
        //         "packageMarks": "Fragile glass bottle",
        //         "declarationNotes": [
        //           {
        //             "value": "up to three declaration notes"
        //           }
        //         ],
        //         "exportReference": "export reference",
        //         "exportReason": "export reason",
        //         "exportReasonType": "permanent",
        //         "licenses": [
        //           {
        //             "typeCode": "export",
        //             "value": "123127233"
        //           }
        //         ],
        //         "shipmentType": "personal",
        //         "customsDocuments": [
        //           {
        //             "typeCode": "INV",
        //             "value": "MyDHLAPI - CUSDOC-001"
        //           }
        //         ]
        //       },
        //       "description": "Shipment",
        //       "USFilingTypeValue": "12345",
        //       "incoterm": "DAP",
        //       "unitOfMeasurement": "metric"
        //     },
        //     "shipmentNotification": [
        //       {
        //         "typeCode": "email",
        //         "receiverId": "shipmentnotification@mydhlapisample.com",
        //         "languageCode": "eng",
        //         "languageCountryCode": "UK",
        //         "bespokeMessage": "message to be included in the notification"
        //       }
        //     ],
        //     "getTransliteratedResponse": false,
        //     "estimatedDeliveryDate": {
        //       "isRequested": true,
        //       "typeCode": "QDDC"
        //     },
        //     "getAdditionalInformation": [
        //       {
        //         "typeCode": "pickupDetails",
        //         "isRequested": true
        //       }
        //     ]
        //   }';
        //   $response = Http::withHeaders([
        //     'content-type' => 'application/json',
        //     'Message-Reference' => 'd0e7832e-5c98-11ea-bc55-0242ac13', 
        //     'Authorization' => 'Basic ' . base64_encode($username . ':' . $password) 
        // ])->post('https://express.api.dhl.com/mydhlapi/test/shipments', json_decode($bodyJson, true));
       
        // return $response->json();