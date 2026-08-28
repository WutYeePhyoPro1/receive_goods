function formatComma(number, decimal = 2) {

    return Number(number).toLocaleString('en-US', {
        minimumFractionDigits: decimal,
        maximumFractionDigits: decimal
    });

}