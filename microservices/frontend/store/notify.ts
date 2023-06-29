export interface Notification {
    message: string;
    type: 'error' | 'warning' | 'success' | 'info';
    notifyTime: number;
}

export const useNotify = defineStore('notify', () => {
    const notifications: Ref<Notification[]> = ref([]);

    const notify = (messageOrError: unknown, type: 'error' | 'warning' | 'success' | 'info', time: number = 2000) => {
        let message: string = "";
        if (messageOrError instanceof Error) message = messageOrError.message;
        if (typeof messageOrError === "string") message = messageOrError;
        const notification: Notification = { message, type, notifyTime: Date.now() };
        notifications.value?.push(notification);
        setTimeout(removeNotification, time, notification);
    }

    const removeNotification = (notification: Notification) => {
        notifications.value = notifications.value?.filter(n => n.notifyTime != notification.notifyTime);
    }
    return { notify, notifications, removeNotification }
});