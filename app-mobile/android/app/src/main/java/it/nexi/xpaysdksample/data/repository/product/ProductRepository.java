/*
 * In the ProductRepository invoke the WebServiceProduct methods
 * to retrieve the list of products in the cart
 */

package it.nexi.xpaysdksample.data.repository.product;

import it.nexi.xpaysdksample.data.network.product.IWebServiceProduct;
import it.nexi.xpaysdksample.data.network.response.ProductResponse;

public class ProductRepository implements IProductRepository {

    private IWebServiceProduct mWebServiceProduct;

    public ProductRepository(IWebServiceProduct webServiceProduct){
        mWebServiceProduct = webServiceProduct;
    }

    @Override
    public ProductResponse getAllProduct() {
        return mWebServiceProduct.loadAll();
    }
}
