import { phoneNumberValidator } from '~~/utils/phoneValidator';
import { setLocale, addMethod, string } from "yup";

export default defineNuxtPlugin((nuxtApp) => {
    setLocale({
        mixed: {
            required: "لطفا ${path} را وارد نمایید.",
            oneOf: "${path} نامعتبر میباشد."
        },
        string: {
            email: "ایمیل نامعتبر است.",
            min: "${label} باید حداقل ${min} کاراکتر باشد",
            max: "${label} باید حداکثر ${max} کاراکتر باشد",
            length: "${label} باید ${length} کارکتر باشد.",
        },
        number: {
            min: "${label} باید حداقل ${min} باشد",
            max: "${label} باید حداکثر ${max} باشد",
            positive: "${label}, معتبر نیست."
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

    addMethod(string, "nationalId", function phoneNumber() {
        return this.test(
            "nationalId",
            "کد ملی نامعتبر می باشد.",
            function (value) {
                if (value === undefined) return true;
                return validateNationalId(value.toString());
            },
        )
    });
});

