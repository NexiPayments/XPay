//
//  IntExtensions.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 03/09/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import Foundation

extension Int {
    // Return the parsed amount to 2 decimals
    func parseAmount() -> String {
        return "€ \(String(format: "%.2f", Float(self) / 100))"
    }
}
