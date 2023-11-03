//
//  ProductRepository.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import Foundation

class ProductRepository: ProductRepositoryProtocol {
    
    private var productWebService: WebServiceProductProtocol?
    private var amount: Float = 0
    
    init(productWebService: WebServiceProductProtocol = WebServiceProductMock()) {
        self.productWebService = productWebService
    }
    
    // Get list of products
    func getProducts() -> [Product] {
        let products = productWebService!.getProducts().products
        amount = products.reduce(0) { $0 + $1.price! }
        return products
    }
    
    // Convert into euro cents
    func getAmount() -> Int {
        return Int(amount * 100)
    }
    
    // Return the transaction code
    func getTransactionCode() -> String {
        return "XPAYSAMPLE-\(Date().timeIntervalSince1970)"
    }
}
