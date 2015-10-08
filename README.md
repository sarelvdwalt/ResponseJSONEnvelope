# ResponseJSONEnvelope
A simple object that can be used to put a response (in JSON, given as an array) inside a standard envelope, giving you a place to put certain meta-data, including the response-code.

# Installation

    composer require sarelvdwalt/response-json-envelope

# Usage

It is intended to be used inside a Symfony 2+ standard install.

Inside one of your controllers, use it like this:

When returning a success:

    return new ResponseJSON($payloadObject, Response::HTTP_OK);

When returning a failure (example the object is not found):

    return new ResponseJSON(null, Response::HTTP_NOT_FOUND, null);
