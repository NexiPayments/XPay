package it.nexi.xpaysdksample.data.network.product;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.lang.reflect.Type;

import it.nexi.xpaysdksample.data.network.response.ProductResponse;

public class WebServiceProduct implements IWebServiceProduct {

    // Fake API that return a ProductResponse object that contains all products in the cart
    @Override
    public ProductResponse loadAll() {
        String json =
                "{" +
                "                    \"products\":"+
                "                                   [\n" +
                "                                        {\n" +
                "                                            \"id\": \"MAC001\",\n" +
                "                                            \"name\": \"Computer\",\n" +
                "                                            \"imageUrl\": \"https://cdn2.iconfinder.com/data/icons/circle-icons-1/64/computer-256.png\",\n" +
                "                                            \"formattedPrice\": \"€ 0.01\",\n" +
                "                                            \"price\": 0.01\n" +
                "                                        },\n" +
                "                                        {\n" +
                "                                            \"id\": \"HEAD001\",\n" +
                "                                            \"name\": \"Headphones\",\n" +
                "                                            \"imageUrl\": \"https://cdn2.iconfinder.com/data/icons/thesquid-ink-40-free-flat-icon-pack/64/headphone-big-256.png\",\n" +
                "                                            \"formattedPrice\": \"€ 0.01\",\n" +
                "                                            \"price\": 0.01\n" +
                "                                        },\n" +
                "                                        {\n" +
                "                                            \"id\": \"SHIRT001\",\n" +
                "                                            \"name\": \"T-shirt\",\n" +
                "                                            \"imageUrl\": \"https://cdn0.iconfinder.com/data/icons/kameleon-free-pack-rounded/110/T-Shirt-2-256.png\",\n" +
                "                                            \"formattedPrice\": \"€ 0.01\",\n" +
                "                                            \"price\": 0.01\n" +
                "                                        },\n" +
                "                                        {\n" +
                "                                            \"id\": \"SHOES001\",\n" +
                "                                            \"name\": \"Sneakers shoes\",\n" +
                "                                            \"imageUrl\": \"https://cdn3.iconfinder.com/data/icons/fitness-24/512/2-256.png\",\n" +
                "                                            \"formattedPrice\": \"€ 0.01\",\n" +
                "                                            \"price\": 0.01\n" +
                "                                        }\n" +
                "                                    ]\n" +
                "}";


        // Convert JSON to ProductResponse object
        Gson gson = new Gson();
        Type listType = new TypeToken<ProductResponse>(){}.getType();
        return gson.fromJson(json, listType);
    }
}
