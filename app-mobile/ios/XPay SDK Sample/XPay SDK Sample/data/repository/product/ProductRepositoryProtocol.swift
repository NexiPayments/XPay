//
//  ProductRepositoryProtocol.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

protocol ProductRepositoryProtocol {
    func getProducts() -> [Product]
    func getAmount() -> Int
    func getTransactionCode() -> String
}
