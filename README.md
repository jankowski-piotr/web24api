## About Task
### Original message:

REST API Utwórz REST API przy użyciu frameworka Laravel / Symfony. Celem aplikacji jest umożliwienie przesłania przez użytkownika informacji odnośnie firmy(nazwa, NIP, adres, miasto, kod pocztowy) oraz jej pracowników(imię, nazwisko, email, numer telefonu(opcjonalne)) - wszystkie pola są obowiązkowe poza tym które jest oznaczone jako opcjonalne. Uzupełnij endpointy do pełnego CRUDa dla powyższych dwóch. Zapisz dane w bazie danych. PS. Stosuj znane Ci dobre praktyki wytwarzania oprogramowania oraz korzystaj z repozytorium kodu.

- Firma: nazwa, NIP, adres, miasto, kod pocztow
- Pracownik: imię, nazwisko, email, numer telefonu(opcjonalne)


## Building openAi Swagger
run 
    ./vendor/bin/openapi --output public/docs/openapi.json app/Http/Controllers app/Http/Requests app/Http/Resources
