//
//  AboutPresenter.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 03/09/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import Foundation
import XPaySDK

class AboutPresenter: AboutPresenterProtocol {
    
    private var view: AboutViewProtocol?
    
    init(_ view: AboutViewProtocol) {
        self.view = view
    }
    
    func initViews() {
        loadAppInfo()
        loadSDKInfo()
    }
    
    // Load the application informations
    private func loadAppInfo() {
        let appName = Bundle.main.infoDictionary![kCFBundleNameKey as String] as! String
        let appVersion = Bundle.main.infoDictionary![kCFBundleVersionKey as String] as! String
        view?.updateApp(name: appName, version: appVersion)
    }
    
    // Load the XpaySDK informations
    private func loadSDKInfo() {
        let SDKVersion = String("1.1.0")
        view?.updateSDK(version: SDKVersion)
    }
}
