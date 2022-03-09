//
//  FirstViewController.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 27/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit
import XPaySDK

class HomeViewController: ApplePayViewController, HomeViewProtocol, UITableViewDataSource, UITableViewDelegate {
    
    private var presenter: HomePresenterProtocol?
    
    // List of products in shopping cart
    private var products = [Product]()
    @IBOutlet weak var tableViewProducts: UITableView!
    @IBOutlet weak var labelTotalAmount: UILabel!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        tableViewProducts.delegate = self
        tableViewProducts.dataSource = self
        presenter = HomePresenter(view: self)
        presenter?.onViewLoaded()
    }

    func numberOfSections(in tableView: UITableView) -> Int {
        return 1
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return products.count
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "productViewCell", for: indexPath) as! ProductViewCell
        let product = products[indexPath.row]
        cell.imageProduct.downloaded(from: product.imageUrl!)
        cell.labelProductName.text = product.name
        cell.labelProductPrice.text = product.formattedPrice
        return cell
    }

    func refreshProductList(products: [Product]) {
        self.products = products
        tableViewProducts.reloadData()
    }
    
    // Display the amount rounded to 2 decimals
    func refreshTotalAmount(amount: Int) {
        labelTotalAmount.text = amount.parseAmount()
    }
    
    // Pay using the front office page
    @IBAction func payFrontOffice(_ sender: UIButton) {
        presenter?.onFrontOfficeClicked()
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if segue.identifier == "seguePaymentDetails" {
            if let detailsController = segue.destination as? CardInputViewProtocol {
                // Display the payment details page (input card)
                presenter?.goToPaymentDetails(detailsController)
            }
        }
    }
    
    func displaySimpleAlert(title: String, message: String) {
        let alert = UIAlertController(title: title, message: message, preferredStyle: .alert)
        
        alert.addAction(UIAlertAction(title: "Ok", style: .cancel, handler: nil))
        
        DispatchQueue.main.async {
            self.dismiss { self.present(alert, animated: true) }
        }
    }
    
    @IBAction func payWithApple(_ button: UIButton) {
        presenter?.payApple()
    }
    
    func showPaymentOptionDialog(title: String, message: String) {
        let alert = UIAlertController(title: title, message: message, preferredStyle: .actionSheet)
        
        alert.addAction(UIAlertAction(title: "WKWebView", style: .default, handler: { (alert) in
            self.presenter?.onWebViewChoosed(self)
        }))
        alert.addAction(UIAlertAction(title: "Safari Controller", style: .default, handler:{ (alert) in
            self.presenter?.onSafariChoosed(self)
        }))
        alert.addAction(UIAlertAction(title: "Cancel", style: .cancel, handler: nil))
        
        self.present(alert, animated: true, completion: nil)
    }
    
    // Go to result page
    func goToResult(codTrans: String, amount: Int) {
        let resultController = storyboard?.instantiateViewController(withIdentifier: "resultView") as! ResultViewController
        resultController.amount = amount
        // Remove all presented View Controllers
        DispatchQueue.main.async {
            self.dismiss {
                self.present(UINavigationController(rootViewController: resultController), animated: true, completion: {
                    // After that pop to root the current navigation controller
                    self.navigationController?.popToRootViewController(animated: true)
                })
            }
        }
    }
    
    fileprivate func dismiss(action: @escaping () -> ()) {
        // Check if ViewController already presenting
        if self.presentedViewController != nil {
            self.dismiss(animated: true, completion: action)
        } else {
            action()
        }
    }
}

