package it.nexi.xpaysdksample.data.repository.order;

import org.json.JSONException;
import org.json.JSONObject;

public class OrderRepository implements IOrderRepository {

    @Override
    public JSONObject getBillingFields() {
        try {
            return new JSONObject()
                .put("format", "FULL")
                .put("phoneNumberRequired", true);
        } catch (JSONException e) {
            return null;
        }
    }

    @Override
    public JSONObject getShippingFields() {
        try {
            return new JSONObject()
                    .put("phoneNumberRequired", true);
        } catch (JSONException e) {
            return null;
        }
    }

    @Override
    public String getCountry() {
        return "IT";
    }
}
