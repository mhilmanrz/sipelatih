const participants = [
    { id: 1, name: "Sandra Daratista", nip: "25137163871385" },
    { id: 2, name: "Andi Wijaya", nip: "23153786129368" }
];

let selected = [];

const participantList = document.getElementById('participantList');
const selectedList = document.getElementById('selectedList');
const totalCount = document.getElementById('totalCount');
const searchAll = document.getElementById('searchAll');

function renderParticipants(data) {
    participantList.innerHTML = '';
    data.forEach(p => {
        const li = document.createElement('li');

        li.innerHTML = `
            <span>
                <input type="checkbox" onchange="toggleSelect(${p.id})">
                ${p.name} - ${p.nip}
            </span>
        `;
        participantList.appendChild(li);
    });
    totalCount.textContent = data.length;
}

function toggleSelect(id) {
    const person = participants.find(p => p.id === id);
    if (!selected.find(s => s.id === id)) {
        selected.push(person);
    }
    renderSelected();
}

function renderSelected() {
    selectedList.innerHTML = '';
    selected.forEach(p => {
        const li = document.createElement('li');
        li.innerHTML = `
            <span>${p.name} - ${p.nip}</span>
            <button class="btn-remove" onclick="removeSelected(${p.id})">✖</button>
        `;
        selectedList.appendChild(li);
    });
}

function removeSelected(id) {
    selected = selected.filter(p => p.id !== id);
    renderSelected();
}

function resetData() {
    selected = [];
    document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
    renderSelected();
}

searchAll.addEventListener('input', () => {
    const keyword = searchAll.value.toLowerCase();
    const filtered = participants.filter(p =>
        p.name.toLowerCase().includes(keyword) ||
        p.nip.includes(keyword)
    );
    renderParticipants(filtered);
});

renderParticipants(participants);
