export const rangeBetween2Numbers = (start: number, end: number) => {
    const isReverse = (start > end);
    const targetLength = isReverse ? (start - end) + 1 : (end - start) + 1;
    const arr = new Array(targetLength);
    const b = Array.apply(null, arr);
    const result = b.map((discard, n) => {
        return (isReverse) ? n + end : n + start;
    });

    return (isReverse) ? result.reverse() : result;
}