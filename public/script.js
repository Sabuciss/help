// Shared JavaScript for ticket forms (create and edit)
const fileInput = document.getElementById('attachments');
const fileList = document.getElementById('file-list');
const dt = new DataTransfer();

// Initialize if elements exist
if (fileInput && fileList) {
    fileInput.addEventListener('change', function() {
        for (const file of fileInput.files) {
            dt.items.add(file);
        }
        fileInput.files = dt.files;
        updateFileList();
    });

    // Initialize drag and drop if drop zone exists
    const dropZone = document.querySelector('.file-upload-box');
    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = '#e8f4f8';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.backgroundColor = '';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = '';
            for (const file of e.dataTransfer.files) {
                dt.items.add(file);
            }
            fileInput.files = dt.files;
            updateFileList();
        });
    }
}

function updateFileList() {
    if (!fileList) return;

    fileList.innerHTML = '';
    if (fileInput.files.length > 0) {
        const list = document.createElement('ul');
        list.className = 'attachments-list';

        for (let i = 0; i < fileInput.files.length; i++) {
            const file = fileInput.files[i];
            const li = document.createElement('li');
            li.innerHTML = `
                <span>📎 ${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
                <button type="button" onclick="removeFile(${i})" class="btn btn-danger btn-small">Noņemt</button>
            `;
            list.appendChild(li);
        }

        fileList.appendChild(list);
    }
}

function removeFile(index) {
    dt.items.remove(index);
    fileInput.files = dt.files;
    updateFileList();
}

// Edit form specific functions
function deleteAttachment(attachmentId, fileName) {
    if (!confirm(`Vai tiešām vēlaties noņemt pielikumu "${fileName}"?`)) {
        return;
    }

    fetch(`/attachments/${attachmentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the attachment from the list
            const attachmentItem = document.querySelector(`[data-attachment-id="${attachmentId}"]`);
            if (attachmentItem) {
                attachmentItem.remove();
            }
            // Show success message
            showMessage('Pielikums veiksmīgi dzēsts!', 'success');
        } else {
            showMessage('Kļūda dzēšot pielikumu.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Kļūda dzēšot pielikumu.', 'error');
    });
}

function showMessage(message, type) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.message');
    existingMessages.forEach(msg => msg.remove());

    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    messageDiv.style.cssText = `
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 5px;
        font-weight: bold;
        ${type === 'success' ? 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;' : 'background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'}
    `;

    // Insert at the top of the card
    const card = document.querySelector('.card');
    if (card) {
        card.insertBefore(messageDiv, card.firstChild.nextSibling);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
}


// calendar script

const calendarTimeZone = 'Europe/Riga';

function formatLocalDate(date) {
    return date.toLocaleDateString('en-CA', { timeZone: calendarTimeZone });
}

function renderMonth(year, month, tickets, now) {
    const monthNames = ['Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnijs',
                       'Jūlijs', 'Augusts', 'Septembris', 'Oktobris', 'Novembris', 'Decembris'];
    const dayNames = ['Sv', 'P', 'O', 'T', 'C', 'P', 'S'];
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    let monthHTML = `
        <div style="grid-column: 1 / -1; display: grid; grid-template-columns: repeat(7, 1fr); gap: 1rem; padding: 1rem; background: #fff; border-radius: 5px;">
            <div style="grid-column: 1 / -1; text-align: center; margin-bottom: 1rem; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 5px; margin: 0;">
                <h3 style="margin: 0;">${monthNames[month]} ${year}</h3>
            </div>
    `;
    
    // Day headers
    dayNames.forEach(day => {
        monthHTML += `<div style="text-align: center; font-weight: bold; padding: 0.5rem; background: #f0f0f0; border-radius: 3px;">${day}</div>`;
    });
    
    // Empty cells before month starts
    for (let i = 0; i < startingDayOfWeek; i++) {
        monthHTML += `<div></div>`;
    }
    
    // Days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
            const dateStr = formatLocalDate(date);
        
        const dayTickets = tickets.filter(t => {
                return t.created_date_local === dateStr;
        });
        
            const isToday = formatLocalDate(date) === formatLocalDate(now);
        
        let backgroundColor = '#f9f9f9';
        if (isToday) backgroundColor = '#e3f2fd';
        if (dayTickets.length > 0) backgroundColor = '#fff3cd';
        
        let urgentCount = dayTickets.filter(t => t.priority === 'urgent').length;
        
        monthHTML += `
            <div style="
                background-color: ${backgroundColor};
                border: 1px solid #ddd;
                padding: 0.75rem;
                border-radius: 3px;
                min-height: 80px;
                cursor: pointer;
                transition: background-color 0.3s;
            " onmouseover="this.style.backgroundColor='#ecf0f1'" onmouseout="this.style.backgroundColor='${backgroundColor}'">
                <strong style="display: block; margin-bottom: 0.5rem;">${day}</strong>
                <small style="display: block; color: #7f8c8d;">${dayTickets.length} biļete(s)</small>
                ${urgentCount > 0 ? `<span class="badge badge-urgent" style="font-size: 0.75rem;">${urgentCount} steidzama</span>` : ''}
            </div>
        `;
    }
    
    monthHTML += `</div>`;
    return monthHTML;
}

document.addEventListener('DOMContentLoaded', function() {
    // Only run calendar code if we're on the calendar page
    if (document.getElementById('calendar') && window.calendarData) {
        const tickets = window.calendarData;
        const now = new Date();
        const lvNow = new Date(now.toLocaleString('en-US', { timeZone: calendarTimeZone }));
        const currentYear = lvNow.getFullYear();
        const currentMonth = lvNow.getMonth();
        
        let calendarHTML = '';
        
        // Render current month and next 2 months
        for (let i = 0; i < 3; i++) {
            const monthToRender = currentMonth + i;
            const yearToRender = currentYear + Math.floor(monthToRender / 12);
            const adjustedMonth = monthToRender % 12;
            
            calendarHTML += renderMonth(yearToRender, adjustedMonth, tickets, now);
        }
        
        document.getElementById('calendar').innerHTML = calendarHTML;
    }
});