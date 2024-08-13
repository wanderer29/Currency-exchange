# Project "Currency exchange"

REST API for describing currencies and exchange rates. Allows viewing and editing lists of currencies and exchange rates, and calculating the conversion of arbitrary amounts from one currency to another.

The project does not include a web interface.

# Examples

## Currencies endpoints
- Getting a list of currencies: GET /currencies
- Receiving a specific currency: GET /currency/EUR
- Adding a new currency to the database: POST /currencies (format: x-www-form-urlencoded, fields: code, name, sign)

## Exchange rates endpoints
- Get a list of all exchange rates: GET /exchangeRates 
- Getting a specific exchange rate: GET /exchangeRate/USDRUB
- Adding a new exchange rate to the database: POST /exchangeRates (format: x-www-form-urlencoded, fields: baseCurrencyCode, targetCurrencyCode, rate)
- Updating the existing exchange rate in the database: PATCH /exchangeRate/USDRUB (format: x-www-form-urlencoded, field: rate)

## Currency exchange
- Calculation of the transfer of a certain amount of funds from one currency to another: GET /exchange?from=BASE_CURRENCY_CODE&to=TARGET_CURRENCY_CODE&amount=$AMOUNT


