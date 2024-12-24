// JavaScript for interactive elements

document.addEventListener('DOMContentLoaded', () => {
    // Toggle active class for filters
    const filterButtons = document.querySelectorAll('.filters button');
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Filter functionality can be implemented here
            const filter = button.textContent.toLowerCase();
            console.log(`Filtering by: ${filter}`);
        });
    });

    // Add button functionality
    const addButton = document.querySelector('.add-button');
    addButton.addEventListener('click', () => {
        alert('Add button clicked! You can implement a modal or form here.');
    });

    // Interactive search bar
    const searchBar = document.querySelector('.search-bar');
    searchBar.addEventListener('input', (event) => {
        const searchTerm = event.target.value.toLowerCase();
        const items = document.querySelectorAll('.item');

        items.forEach(item => {
            const itemName = item.querySelector('h2').textContent.toLowerCase();
            if (itemName.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Example: Populating item list dynamically (optional)
    const itemList = document.querySelector('.item-list');
    const sampleData = [
        { name: 'Apples', quantity: '3 kilograms', status: 'Available', expiry: '5 days', image: 'apples.jpg' },
        { name: 'Oranges', quantity: '2 kilograms', status: 'Low', expiry: '2 days', image: 'oranges.jpg' }
    ];

    sampleData.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.classList.add('item');

        itemDiv.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <div class="item-details">
                <h2>${item.name}</h2>
                <p>${item.expiry} to expire</p>
            </div>
            <div class="item-meta">
                <span class="quantity">${item.quantity}</span>
                <span class="status ${item.status.toLowerCase()}">${item.status}</span>
            </div>
        `;

        itemList.appendChild(itemDiv);
    });
});



