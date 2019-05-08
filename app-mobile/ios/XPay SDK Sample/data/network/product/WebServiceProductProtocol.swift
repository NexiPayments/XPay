//
//  WebServiceProductProtocol.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

protocol WebServiceProductProtocol {
    // Get shopping cart products
    func getProducts() -> ProductResponse
}
