package it.nexi.xpaysdksample.data.network.product;

import it.nexi.xpaysdksample.data.network.response.ProductResponse;

public interface IWebServiceProduct {
    // Return all product in shopping cart
    ProductResponse loadAll();
}
