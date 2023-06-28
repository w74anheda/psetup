export interface Countdown {
    days: number;
    hours: number;
    minutes: number;
    seconds: number;
}

export const calculateCountdown = (targetDate: Date): Countdown => {
    const now = new Date();
    const difference = targetDate.getTime() - now.getTime();
    if (difference <= 0) {
        return {
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
        };
    }

    const days = Math.floor(difference / (1000 * 60 * 60 * 24));
    const hours = Math.floor((difference / (1000 * 60 * 60)) % 24);
    const minutes = Math.floor((difference / 1000 / 60) % 60);
    const seconds = Math.floor((difference / 1000) % 60);

    return {
        days,
        hours,
        minutes,
        seconds,
    };
};

export const twoDigitsFormat = (n: number) => {
    return n < 10 ? "0" + n : n;
};

export const getNextMinutes = (minute: number): string => {
    const currentDate = new Date();
    const nextThreeMinutes = new Date(currentDate.getTime() + minute * 60000); // Adding 3 minutes in milliseconds

    const hours = nextThreeMinutes.getHours().toString().padStart(2, "0");
    const minutes = nextThreeMinutes.getMinutes().toString().padStart(2, "0");
    const seconds = nextThreeMinutes.getSeconds().toString().padStart(2, "0");

    return `${hours}:${minutes}:${seconds}`;
};