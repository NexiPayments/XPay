//
//  WebServiceProduct.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//


///
/// Fake API to get products list
///
import Foundation

class WebServiceProductMock: WebServiceProductProtocol {
    
    private let jsonString = """
                                {
                                    "products": [
                                        {
                                            "id": "MAC001",
                                            "name": "Computer",
                                            "imageUrl": "https://cdn2.iconfinder.com/data/icons/circle-icons-1/64/computer-256.png",
                                            "formattedPrice": "€ 1399.00",
                                            "price": 1399.0
                                        },
                                        {
                                            "id": "HEAD001",
                                            "name": "Headphones",
                                            "imageUrl": "https://cdn2.iconfinder.com/data/icons/thesquid-ink-40-free-flat-icon-pack/64/headphone-big-256.png",
                                            "formattedPrice": "€ 69.90",
                                            "price": 69.90
                                        },
                                        {
                                            "id": "SHIRT001",
                                            "name": "T-shirt",
                                            "imageUrl": "https://cdn0.iconfinder.com/data/icons/kameleon-free-pack-rounded/110/T-Shirt-2-256.png",
                                            "formattedPrice": "€ 9.90",
                                            "price": 9.90
                                        },
                                        {
                                            "id": "SHOES001",
                                            "name": "Shoes",
                                            "imageUrl": "https://cdn3.iconfinder.com/data/icons/fitness-24/512/2-256.png",
                                            "formattedPrice": "€ 99.90",
                                            "price": 99.90
                                        }
                                    ]
                                }
                            """
    
    func getProducts() -> ProductResponse {
        return try! JSONDecoder().decode(ProductResponse.self, from: Data(jsonString.utf8))
    }
}
