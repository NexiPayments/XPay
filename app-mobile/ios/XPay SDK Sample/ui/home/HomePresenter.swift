//
//  HomePresenter.swift
//  XPay SDK Sample
//
//  Created by Nexi Spa on 28/08/18.
//  Copyright © 2018 Nexi Spa. All rights reserved.
//

import UIKit
import XPaySDK

class HomePresenter : HomePresenterProtocol {
    
    private var view: HomeViewProtocol?
    private var interactor: HomeInteractorProtocol?
    
    private var productRepo: ProductRepositoryProtocol?
    private var paymentRepo: PaymentRepositoryProtocol?
    
    private var totalAmount: Int = 0
    private var codTrans: String = ""
    
    init(view: HomeViewProtocol, productRepo: ProductRepositoryProtocol = ProductRepository(), paymentRepo: PaymentRepositoryProtocol = PaymentRepository()) {
        self.view = view
        self.productRepo = productRepo
        self.paymentRepo = paymentRepo
        self.interactor = HomeInteractor()
    }
    
    func onViewLoaded() {
        // Load products using the mock API
        loadProducts()
        // Load merchant settings
        loadAppSettings()
        // Observe merchant settings changes
        NotificationCenter.default.addObserver(self, selector: #selector(HomePresenter.loadAppSettings), name: UserDefaults.didChangeNotification, object: nil)
    }
    
    private func loadProducts() {
        // Get products and amount from repository
        let products = productRepo!.getProducts()
        totalAmount = productRepo!.getAmount()
        // Update the view
        view?.refreshProductList(products: products)
        view?.refreshTotalAmount(amount: totalAmount)
    }
    
    func onWebViewChoosed(_ parent: UIViewController) {
        // Retrieve the transaction code
        codTrans = productRepo!.getTransactionCode()
        // Go to payment page
        self.paymentRepo?.pay(parent, codTrans: self.codTrans, amount: self.totalAmount, handler: { response in
            self.handleFrontOffice(response)
        })
    }
    
    func onSafariChoosed(_ parent: UIViewController) {
        // Retrieve the transaction code
        codTrans = productRepo!.getTransactionCode()
        // Go to payment page
        self.paymentRepo?.paySafari(parent, codTrans: self.codTrans, amount: self.totalAmount, handler: { response in
            self.handleFrontOffice(response)
        })
    }
    
    func onFrontOfficeClicked() {
        let title = Bundle.main.infoDictionary![kCFBundleNameKey as String] as! String
        view?.showPaymentOptionDialog(title: title, message: "Which technology do you want to use to pay?")
    }
    
    func goToPaymentDetails(_ view: CardInputViewProtocol) {
        if let vc = view as? CardInputViewController {
            codTrans = productRepo!.getTransactionCode()
            vc.amount = totalAmount
            vc.codTrans = codTrans
        }
    }

    ///
    /// Handle the front office result
    ///
    private func handleFrontOffice(_ response: ApiFrontOfficeQPResponse) {
        let title = "Front office"
        var message = "Payment was canceled by user"
        if response.IsValid {
            if response.Error != nil && !(response.Error!.Message).isEmpty {
                message = "Error during payament process: \(response.Error!.Message)"
            }
            else if !response.IsCanceled {
                message = "Payment was successful with the circuit \(response.Brand!)"
            }
        } else {
            message = "There are errors during payment process"
        }
        view?.displaySimpleAlert(title: title, message: message)
    }  
    
    ///
    /// Payment process managed by Apple Pay
    ///
    func payApple() {
        // Retrieve the transaction code
        codTrans = productRepo!.getTransactionCode()
        let appleRequest = interactor?.getAppleRequest(amount: totalAmount, codTrans: codTrans)
        do {
            try view?.payWithApple(request: appleRequest!, handler: { (response, error) in
                self.handleApplePay(response, error)
            })
        } catch let error as XPayError {
            print(error.description!)
        } catch {
            print(error.localizedDescription)
        }
    }
    
    ///
    /// Handle the apple pay result
    ///
    private func handleApplePay(_ response: ApiApplePayResponse?, _ error: ApiErrorResponse?) {
        let title = "Apple Pay"
        var message = "Payment was canceled by user"
        if error != nil {
            message = "Error: \(error!.Error.Message)"
        } else {
            if response != nil {
                if response!.IsSuccess {
                    // If payment was completed
                    self.view?.goToResult(codTrans: codTrans, amount: totalAmount)
                    return
                } else {
                    message = "Error: \(response!.Error.Message)"
                }
            }
        }
        view?.displaySimpleAlert(title: title, message: message)
    }
    
    /// Reload merchant settings
    @objc func loadAppSettings() {
        SettingsHelper.setMerchantSettings()
        SettingsHelper.setEnvironment()
        SettingsHelper.setApplePay()
        // Reload repository settings
        paymentRepo?.loadSettings()
    }
}

