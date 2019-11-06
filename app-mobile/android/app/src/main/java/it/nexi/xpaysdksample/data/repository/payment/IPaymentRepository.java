package it.nexi.xpaysdksample.data.repository.payment;

import java.util.Date;

import it.nexi.xpay.CallBacks.ApiResponseCallback;
import it.nexi.xpay.CallBacks.FrontOfficeCallbackQP;
import it.nexi.xpay.GooglePay.GooglePayRequest;
import it.nexi.xpay.Models.WebApi.Responses.BackOffice.ApiReportOrdiniResponse;
import it.nexi.xpay.Utils.BackOffice.ReportOrdiniUtils;

public interface IPaymentRepository {

    void pay(String transactionCode, int amount, FrontOfficeCallbackQP handler);
    void payChrome(String transactionCode, int amount, FrontOfficeCallbackQP handler);
    void ordersReport (Date from,
                       Date to,
                       ReportOrdiniUtils.Channel channel,
                       ReportOrdiniUtils.Status[] statutes,
                       String transactionCode,
                       ApiResponseCallback<ApiReportOrdiniResponse> handler);

    GooglePayRequest getGooglePayRequest(String merchantName, String country, long amount, String transactionCode);
}
