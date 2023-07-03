import { phoneNumberValidator } from '~~/utils/phoneValidator';
import { setLocale, addMethod, string } from "yup";

export default defineNuxtPlugin((nuxtApp) => {
    setLocale({
        mixed: {
            required: "لطفا ${path} را وارد نمایید.",
        },
        string: {
            email: "ایمیل نامعتبر است.",
            min: "${label} باید حداقل ${min} کاراکتر باشد",
            max: "${label} باید حداکثر ${max} کاراکتر باشد",
        },
        number: {
            min: "${label} باید حداقل ${min} باشد",
            max: "${label} باید حداکثر ${max} باشد",
        },
    });
    addMethod(string, "phone", function phoneNumber() {
        return this.test(
            "phone",
            "شماره موبایل نامعتبر می باشد.",
            function (value) {
                if (value === undefined) return true;
                return phoneNumberValidator(value.toString());
            },
        ).matches(
            /^(?:0|98|\+98|\+980|0098|098|00980)?(9\d{9})$/,
            "شماره موبایل را به شکل صحیح وارد نمایید."
          )
    });
});
