package it.nexi.xpaysdksample.data.repository.payment;

import android.content.Context;
import android.util.Log;
import android.webkit.WebView;

import java.io.UnsupportedEncodingException;
import java.util.Date;

import it.nexi.xpay.CallBacks.ApiResponseCallback;
import it.nexi.xpay.CallBacks.FrontOfficeCallbackQP;
import it.nexi.xpay.GooglePay.GooglePayRequest;
import it.nexi.xpay.Models.WebApi.Requests.BackOffice.ApiReportOrdiniRequest;
import it.nexi.xpay.Models.WebApi.Requests.FrontOffice.ApiFrontOfficeQPRequest;
import it.nexi.xpay.Models.WebApi.Responses.BackOffice.ApiReportOrdiniResponse;
import it.nexi.xpay.Utils.BackOffice.ReportOrdiniUtils;
import it.nexi.xpay.Utils.Exceptions.DeviceRootedException;
import it.nexi.xpay.Utils.Exceptions.MacException;
import it.nexi.xpay.Utils.QPUtils.CurrencyUtilsQP;
import it.nexi.xpay.Utils.XPayLogger;
import it.nexi.xpay.XPay;
import it.nexi.xpaysdksample.utils.Constants;

import static it.nexi.xpaysdksample.utils.Constants.ENVIRONMENT;

/**
 * In the PaymentRepository invoke the XPaySDK methods
 * to pay or make others payment related operations
 */

public class PaymentRepository implements IPaymentRepository {

    private static final String TAG = "XPay";
    private XPay xPay;
    private Context context;

    public PaymentRepository(Context context) {
        this.context = context;
    }

    // Instance XPay
    private void setXPay() {
        // Init the XPay class with the QP Secret Key
        try {
            xPay = new XPay(context, Constants.SECRET_KEY);
        } catch (DeviceRootedException e) {
            Log.e(TAG, "Rooted device detected.");
        }
    }

    // Generate ApiFrontOfficeQp request
    private ApiFrontOfficeQPRequest getFrontOfficeRequest(String transactionCode, int amount) {
        // Create the API request using the QP Alias
        ApiFrontOfficeQPRequest apiFrontOfficeQPRequest = null;
        try {
            apiFrontOfficeQPRequest = new ApiFrontOfficeQPRequest(Constants.ALIAS,
                    transactionCode, CurrencyUtilsQP.EUR, amount);
        } catch (UnsupportedEncodingException | MacException e) {
            e.printStackTrace();
        }
        return apiFrontOfficeQPRequest;
    }

    // Pay with front office page (QP method with WebView)
    @Override
    public void pay(String transactionCode, int amount, FrontOfficeCallbackQP handler) {
        setXPay();
        ApiFrontOfficeQPRequest apiFrontOfficeQPRequest = getFrontOfficeRequest(transactionCode, amount);

        // Set the demo environment
        xPay.FrontOffice.setEnvironment(ENVIRONMENT);
        xPay.FrontOffice.paga(apiFrontOfficeQPRequest, true, handler);
    }

    // Pay with front office page (QP method with ChromeTab)
    @Override
    public void payChrome(String transactionCode, int amount, FrontOfficeCallbackQP handler) {
        setXPay();
        ApiFrontOfficeQPRequest apiFrontOfficeQPRequest = getFrontOfficeRequest(transactionCode, amount);

        // Set the demo environment
        xPay.FrontOffice.setEnvironment(ENVIRONMENT);
        xPay.FrontOffice.pagaChrome(apiFrontOfficeQPRequest, handler);
    }


    @Override
    public void ordersReport(Date from, Date to, ReportOrdiniUtils.Channel channel, ReportOrdiniUtils.Status[] statutes, String transactionCode,
                             ApiResponseCallback<ApiReportOrdiniResponse> handler) {

        // Init the XPay class with the QP Secret Key
        try {
            xPay = new XPay(context, Constants.SECRET_KEY);
        } catch (DeviceRootedException e) {
            Log.e(TAG, "Rooted device detected.");
        }
        // Create the API orders report request using the API Alias
        ApiReportOrdiniRequest apiReportOrdiniRequest = new ApiReportOrdiniRequest(
                Constants.ALIAS,
                from,
                to,
                channel,
                statutes,
                transactionCode
        );
        // Set the demo environment
        xPay.BackOffice.setEnvironment(ENVIRONMENT);
        xPay.BackOffice.reportOrdini(apiReportOrdiniRequest, handler);
    }

    @Override
    public GooglePayRequest getGooglePayRequest(String merchantName, String country, long amount, String transactionCode) {
        GooglePayRequest googlePayRequest = new GooglePayRequest(
                Constants.ALIAS, Constants.SECRET_KEY, Constants.TERMINAL_ID, CurrencyUtilsQP.EUR, country, amount, merchantName, transactionCode);
        googlePayRequest.setEnvironment(ENVIRONMENT);
        return googlePayRequest;
    }
}