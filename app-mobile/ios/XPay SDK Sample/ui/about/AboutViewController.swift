//
//  SecondViewController.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 27/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit

class AboutViewController: UITableViewController, AboutViewProtocol {
    @IBOutlet weak var labelAppName: UILabel!
    @IBOutlet weak var labelAppVersion: UILabel!
    @IBOutlet weak var labelSDKVersion: UILabel!
    
    private var presenter: AboutPresenterProtocol?
    
    let dateFormatter = DateFormatter()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        presenter = AboutPresenter(self)
        // Start init views inside this View Controller
        presenter?.initViews()
    }

    // Display the application details
    func updateApp(name: String, version: String) {
        labelAppName.text = name
        labelAppVersion.text = version
    }
    
    // Display the XpaySDK details
    func updateSDK(version: String) {
        labelSDKVersion.text = version
    }
}

