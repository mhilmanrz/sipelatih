const ctx = document.getElementById('jplChart').getContext('2d');

const jplChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Nina Persada', 'Saskya Gok'],
        datasets: [
            {
                label: 'Target JPL',
                data: [24, 24],
                borderColor: '#0a7f82',
                backgroundColor: 'rgba(10,127,130,0.2)',
                tension: 0.4
            },
            {
                label: 'Capaian JPL',
                data: [50, 20],
                borderColor: '#1db954',
                backgroundColor: 'rgba(29,185,84,0.2)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});