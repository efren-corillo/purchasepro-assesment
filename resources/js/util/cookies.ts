import Cookies from 'js-cookie';

const token: string = 'token';

export function deleteAllCookies() {
    let cookies: Object = Cookies.get();
    let k: keyof typeof cookies;

    // ensures all cookies are removed.
    for (k in cookies) {
        Cookies.remove(k);
    }
}

export function setTokenCookie(string: string, options: Object = {sameSite: 'strict'}) {
    Cookies.set(token, string, options)
}

export function getTokenCookie() {
    return Cookies.get(token)
}

export function removeTokenCookie() {
    Cookies.remove(token)
}