
function arrayToCSV(data) {
    const headers = Object.keys(data[0]).join(',');
    const rows = data.map(obj => Object.values(obj).join(','));
    return [headers, ...rows].join('\n');
}

function downloadCSV(content, filename) {
    const blob = new Blob([content], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('href', url);
    a.setAttribute('download', filename + '.csv');
    a.click();
    URL.revokeObjectURL(url);
}

function dateString() {
    const date = new Date();
    return date.toISOString().split('T')[0];
}

export {dateString, downloadCSV, arrayToCSV}