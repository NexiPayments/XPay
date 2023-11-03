//
//  HomePresenterProtocol.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit

protocol HomePresenterProtocol {
    func onViewLoaded()
    func onWebViewChoosed(_ parent: UIViewController)
    func onSafariChoosed(_ parent: UIViewController)
    func onFrontOfficeClicked()
    func goToPaymentDetails(_ view: CardInputViewProtocol)
    func payApple()
}
