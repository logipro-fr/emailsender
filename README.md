# EmailSender

Allow to Post on social networks.

# Install

```console
git clone git@github.com:logipro-fr/emailsender.git
```

# To Contribute to EmailSender

### Requirements

- Docker
- Git
- Brevo API access

#### Brevo API Access

To use `EmailSender`, you need a Brevo API key. Brevo is a powerful email marketing platform that offers a variety of services, including transactional and marketing emails. Follow these steps to obtain your API key:

1. **Create a Brevo Account**:
   - Visit the [Brevo website](https://www.brevo.com) and sign up for an account if you don't already have one.

2. **Access API Key Settings**:
   - Once logged in, navigate to the **SMTP & API** section in the Brevo dashboard. You can find this under the "Transactional" menu.

3. **Generate an API Key**:
   - In the **API Keys** tab, click on the **Create a New API Key** button.
   - Enter a name for your API key to easily identify its use (e.g., "EmailSender Project").
   - Click **Generate**. Your new API key will be displayed. **Copy this key** and store it securely, as you'll need it for your application configuration.

4. **Configure Your Application**:
   - Before testing, you need to set up a `secret.env` file at the root of the project containing the following field:

     ```plaintext
     BREVO_API_KEY=YOUR_BREVO_API_KEY
     ```

   - Replace `YOUR_BREVO_API_KEY` with the API key you generated in the Brevo dashboard.

5. **Use the API Key**:
   - The `BREVO_API_KEY` allows your application to authenticate with Brevo's API, enabling you to send emails programmatically.

**Important Note**: Keep your API key confidential and do not share it publicly or commit it to version control systems. If you suspect that your API key has been compromised, revoke it immediately from the Brevo dashboard and generate a new one.

--- 

## Unit test

```console
bin/phpunit
```

Using Test-Driven Development (TDD) principles (thanks to Kent Beck and others), following good practices (thanks to Uncle Bob and others).

## Manual tests

```console
./start
```
Then, you can access Swagger at http://172.17.0.1:11602/ in your browser to test different routes. 
You can also access phpMyAdmin at http://172.17.0.1:11690/.

In `docker/mariad/db.env`, you can set a new password for the root user.

To stop the application, use:

```console
./stop
```

## Quality

Some indicators that seem interesting.

* phpcs PSR12
* phpstan level 9
* 100% coverage obtained naturally thanks to the “classic school” TDD approach
* we hunt mutants with “Infection”. We aim for an MSI score of 100% for “panache”


Quick check with:
```console
./codecheck
./bin/phpunit
```

Check coverage with:
```console
bin/phpunit --coverage-html var
```
and view 'var/index.html' with your browser

Check infection with:
```console
bin/infection
```
and view 'var/infection.html' with your browser