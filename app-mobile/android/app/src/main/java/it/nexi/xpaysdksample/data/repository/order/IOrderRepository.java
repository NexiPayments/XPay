package it.nexi.xpaysdksample.data.repository.order;

import org.json.JSONObject;

public interface IOrderRepository {
    /**
     * @return Return the current country code
     */
    String getCountry();

    /**
     * @return Return the billing requested fields
     */
    JSONObject getBillingFields();

    /**
     * @return Return the shipping requested fields
     */
    JSONObject getShippingFields();
}
