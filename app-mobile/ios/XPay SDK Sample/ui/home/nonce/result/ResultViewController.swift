//
//  ResultViewController.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 29/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit

class ResultViewController: UIViewController, ResultViewProtocol {
    @IBOutlet weak var labelAmount: UILabel!
    
    var amount: Int?
    private var presenter: ResultPresenterProtocol?
    
    override func viewDidLoad() {
        presenter = ResultPresenter(self)
        labelAmount.text = amount?.parseAmount()
    }

    // Go to home page (shopping cart)
    @IBAction func donePayment(_ sender: UIButton) {
        presenter?.donePayment()
    }
    
    // Display orders
    @IBAction func showOrders(_ sender: UIButton) {
        presenter?.reportOrders()
    }
    
    // Display simple alert with custom title and message
    func showAlert(title: String, message: String, handler: ResultViewProtocol.AlertHandler) {
        let alert = UIAlertController(title: title, message: message, preferredStyle: .alert)
        
        alert.addAction(UIAlertAction(title: "Ok", style: .cancel, handler: handler))
        
        self.present(alert, animated: true)
    }
    
    // Close result page
    func goToRoot() {
        navigationController?.dismiss(animated: true, completion: nil)
    }
}
