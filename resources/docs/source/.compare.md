---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#general


<!-- START_ae309226f6476a5c4acc7fb3419990bd -->
## api
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "errors": {
        "message": "No tv show requested. Please make your url looks like this : http:\/\/localhost:8000\/api?q=[show name]"
    }
}
```

### HTTP Request
`GET api`


<!-- END_ae309226f6476a5c4acc7fb3419990bd -->


