# Setting up Gmail for contact-form emails

When email is configured, every submission of the website **Contact** form is:

1. saved to **Admin → Enquiries** (this always happens), **and**
2. emailed to a chosen inbox, with **Reply-To** set to the person who wrote in,
   so you can reply to them straight from your mail client.

This guide sets that up using a Gmail (or Google Workspace) account as the sender.

---

## What you need

* A Gmail address to **send from** — e.g. a dedicated `bedeck.enquiries@gmail.com`,
  or a Google Workspace address like `info@bedeckinternational.lk`.
* Access to that account's Google security settings.
* Admin access to the website (`/admin/login`).

> **Why not just the Gmail password?** Google blocks normal passwords for SMTP.
> You must create a one-purpose **App Password**, which requires 2-Step
> Verification to be switched on first.

---

## Step 1 — Turn on 2-Step Verification

1. Sign in to the Gmail account.
2. Go to **[myaccount.google.com/security](https://myaccount.google.com/security)**.
3. Under **"How you sign in to Google"**, open **2-Step Verification** and follow
   the prompts (phone number / authenticator). Finish until it shows **On**.

If it is already on, skip to Step 2.

---

## Step 2 — Create an App Password

1. Go to **[myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)**
   (or Security → 2-Step Verification → scroll to **App passwords**).
2. Enter a name you'll recognise, e.g. `Bedeck website`, and click **Create**.
3. Google shows a **16-character password** in a yellow box, like `abcd efgh ijkl mnop`.
4. **Copy it now** — it is shown only once. Remove the spaces when you paste it
   (`abcdefghijklmnop`).

You can delete/revoke this password anytime from the same page without affecting
the Google account.

*(No "App passwords" option? It only appears once 2-Step Verification is on. On
some Workspace accounts an admin must allow it: Google Admin console → Security →
Access and data control → **Less secure apps / App passwords**.)*

---

## Step 3 — Enter the settings in the admin panel

1. Log in at **`https://your-site/admin/login`**.
2. Open **Site Settings** (left sidebar) and scroll to **"Email delivery (Gmail SMTP)"**.
3. Fill it in:

| Field | Value |
|---|---|
| **Send enquiry emails** | ✅ ticked |
| **Send enquiry notifications to** | the inbox that should receive enquiries (can be any address) |
| **SMTP host** | `smtp.gmail.com` |
| **Port** | `587` |
| **Security** | `STARTTLS (port 587)` |
| **From name** | `Bedeck International` |
| **Gmail address (SMTP username)** | the full sending address, e.g. `bedeck.enquiries@gmail.com` |
| **Gmail App Password** | the 16 characters from Step 2 (no spaces) |
| **From address** | the **same** Gmail address as the username |

4. Click **Save settings**.
5. In the **"Send a test email"** box, enter your own address and click **Send test**.
6. Check that inbox — **and the spam folder** — for *"Bedeck International — SMTP test"*.

Once the test arrives, submit the public Contact form once to confirm the real
notification looks right.

### Notes on the fields

* **Port 587 + STARTTLS** is the normal Gmail setup. If your host blocks port 587,
  switch **Security** to `SSL / TLS (port 465)` and **Port** to `465`.
* **From address must match the Gmail account.** Gmail rewrites the "From" to the
  authenticated address anyway. To make it read `info@bedeckinternational.lk`
  instead, add that address as a verified alias in Gmail
  (Settings → Accounts → **Send mail as**) and then use it as the From address.
* Leaving **Gmail App Password** blank on a later save keeps the existing one.
* Un-tick **Send enquiry emails** to stop sending (enquiries are still saved).

---

## Alternative — set it in `.env` directly

If you don't want to use the admin screen (or `.env` isn't writable by the web
server), edit the project's `.env` file on the server:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_SCHEME=smtp                 # use "smtps" if you switch to port 465
MAIL_USERNAME=bedeck.enquiries@gmail.com
MAIL_PASSWORD=abcdefghijklmnop    # the 16-char App Password, no spaces
MAIL_FROM_ADDRESS=bedeck.enquiries@gmail.com
MAIL_FROM_NAME="Bedeck International"
MAIL_ENQUIRY_TO=info@bedeckinternational.lk
```

Then, from the project directory:

```bash
php artisan config:clear
# if the site runs with a cached config (production), also:
php artisan config:cache
```

`MAIL_MAILER=log` (the local default) means "don't send — just write the email
into `storage/logs/laravel.log`". Change it to `smtp` to actually send.

---

## Troubleshooting

| Symptom | Fix |
|---|---|
| **"Username and Password not accepted" / `535-5.7.8`** | You used the account password, not an App Password — or 2-Step Verification is off. Redo Steps 1–2. |
| **"Connection could not be established" / timeout** | Port 587 is blocked on the network/host. Use `465` + `SSL / TLS`. |
| **Test says sent, but nothing arrives** | Check spam. Check the **"Send enquiry notifications to"** address is correct. Check the sending account's **Sent** folder — if the mail is there, it's a delivery/spam issue, not a config issue. |
| **Works locally, not on the server** | The server likely has a **cached config**. Run `php artisan config:cache` after saving. Also confirm `.env` is writable if you used the admin screen (the page warns if not). |
| **Emails land in spam** | Expected when a `@gmail.com` address sends "as" your domain. Use a Google Workspace address for the domain, or a verified **Send mail as** alias, and keep the From address matching. |
| **Stopped sending after a while** | Free Gmail allows roughly **500 messages/day**; Workspace ~2,000. For this site's volume that's plenty, but a compromised/limited account will silently stop. |

---

## Security

* The **App Password is not** your Google account password. If it leaks, revoke it
  at [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
  and create a new one — the Google account itself stays safe.
* It is stored only in the server's `.env` file. `.env` is **not** committed to
  git and is **not** stored in the database.
* Only signed-in admins can view or change these settings.
