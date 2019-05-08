//
//  Product.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

///
/// Product represents the shopping cart item
///

struct Product: Codable {
    var id: String?
    var name: String?
    var imageUrl: String?
    var formattedPrice: String?
    var price: Float?
}
