class ExpenseManager {
  constructor() {
    this.categorySelect = document.getElementById('categorySelect');
    this.limitInfo = document.getElementById('limitInfo');
    this.limitText = document.getElementById('limitText');
    this.dateInput = document.querySelector('input[name="date"]');
    this.usedAmountElement = document.getElementById('usedAmountText');
    this.limitBalanceElement = document.getElementById('limitBalance');
    this.priceInput = document.querySelector('input[name="price"]');
    this.form = document.querySelector('form');
    this.modalConfirm = document.getElementById('modalConfirm');

    this.currentLimitData = null;
    this.currentUsedAmount = 0;
    this.isSubmitting = false;

    this.init();
  }

  init() {
    this.bindEvents();
    this.updateLimitInfo();
  }

  bindEvents() {
    if (this.categorySelect) {
      this.categorySelect.addEventListener('change', () => this.updateLimitInfo());
    }

    if (this.dateInput) {
      this.dateInput.addEventListener('change', () => this.handleDateChange());
    }

    if (this.priceInput) {
      this.priceInput.addEventListener('input', () => this.handlePriceInput());
    }

    if (this.form) {
      this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    if (this.modalConfirm) {
      this.modalConfirm.addEventListener('click', () => this.handleModalConfirm());
    }
  }

  handlePriceInput() {
    try {
      const currentAmount = this.parseInputAmount();
      this.updateLimitBalance(currentAmount);
    } catch (error) {
      console.error('Error handling price input:', error);
    }
  }

  async handleDateChange() {
    try {
      this.currentLimitData = null;
      await this.updateUsedAmount();
      await this.updateLimitBalance(this.parseInputAmount());
    } catch (error) {
      console.error('Error handling date change:', error);
    }
  }

  parseInputAmount() {
    const value = this.priceInput?.value?.trim();
    if (!value) return 0;

    const cleanValue = value.replace(',', '.');
    const parsed = parseFloat(cleanValue);

    return isNaN(parsed) ? 0 : parsed;
  }

  async updateLimitInfo() {
    console.log('📊 updateLimitInfo: START');

    if (!this.categorySelect) {
      console.error('❌ categorySelect does not exist');
      return;
    }

    const selectedCategory = this.categorySelect.value; 

    if (!selectedCategory || selectedCategory === '' || selectedCategory === 'Wybierz kategorię wydatku') {
      if (this.limitInfo) {
        this.limitInfo.style.display = 'block';
      }
      if (this.limitText) {
        this.limitText.innerHTML = 'Wymagany wybór kategorii';
      }
      if (this.usedAmountElement) {
        this.usedAmountElement.innerHTML = 'Wybierz kategorię aby zobaczyć wykorzystaną kwotę';
      }
      if (this.limitBalanceElement) {
        this.limitBalanceElement.innerHTML = 'Wybierz kategorię, by zobaczyć bilans';
      }

      this.currentLimitData = null; 
      this.currentUsedAmount = 0;  
      return;
    }

    try {
      const limitData = await getLimitForCategory(selectedCategory);
      
      if (this.limitInfo) {
        this.limitInfo.style.display = 'block';
      }

      if (this.limitText) {
        if (limitData && limitData.has_limit && parseFloat(limitData.limit) > 0) {
          this.limitText.innerHTML = `Miesięczny limit dla "${selectedCategory}": <strong>${limitData.limit} zł</strong>`;
        } else {
          this.limitText.innerHTML = 'Brak limitu dla wybranej kategorii';
        }
      }

      this.currentLimitData = null;
      this.currentUsedAmount = 0;

      await this.updateUsedAmount();
      await this.updateLimitBalance();
      
    } catch (error) {
      console.error('❌ updateLimitInfo error:', error);
      if (this.limitText) {
        this.limitText.innerHTML = 'Błąd pobierania danych o limicie';
      }
    }
  }

  async updateUsedAmount() {
    if (!this.usedAmountElement) {
      console.error('❌ usedAmountElement does not exist');
      return;
    }

    const selectedCategory = this.categorySelect?.value;
    const selectedDate = this.dateInput?.value;

    if (!selectedCategory || selectedCategory === 'Wybierz kategorię wydatku') {
      this.usedAmountElement.innerHTML = 'Wybierz kategorię aby zobaczyć wykorzystaną kwotę';
      return;
    }

    try {
      const response = await fetch(`/api/monthly-expenses?category=${encodeURIComponent(selectedCategory)}&date=${encodeURIComponent(selectedDate)}`);

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      this.currentUsedAmount = data.sum || 0;

      if (data.sum > 0) {
        this.usedAmountElement.innerHTML = `<strong>${data.formatted}</strong>`;
      } else {
        this.usedAmountElement.innerHTML = 'Brak wydatków w tym miesiącu dla tej kategorii';
      }
    } catch (error) {
      console.error('Error fetching used amount:', error);
      this.usedAmountElement.innerHTML = 'Błąd pobierania danych';
      this.currentUsedAmount = 0;
    }
  }

  async updateLimitBalance(additionalAmount = 0) {
    if (!this.limitBalanceElement) {
      console.error('❌ limitBalanceElement does not exist');
      return null;
    }

    const selectedCategory = this.categorySelect?.value;
    const selectedDate = this.dateInput?.value;

    if (!selectedCategory || selectedCategory === 'Wybierz kategorię wydatku') {
      this.limitBalanceElement.innerHTML = 'Wybierz kategorię, by zobaczyć bilans';
      return null;
    }

    try {
      if (!this.currentLimitData) {
      this.currentLimitData = await getBalanceLimit(selectedCategory, selectedDate);
      
      if (!this.currentLimitData) {
        throw new Error('Nie udało się pobrać danych o limicie');
      }
    }

      if (this.currentLimitData.has_limit === false) {
        this.limitBalanceElement.innerHTML = '<span>Brak ustawionego limitu</span>';
        return null;
      }

      if (this.currentLimitData.has_limit === true) {
        const totalUsed = this.currentUsedAmount + additionalAmount;
        const balance = this.currentLimitData.limit - totalUsed;

        const balanceClass = balance >= 0 ? 'color: rgb(17, 55, 17)' : 'color: red';
        this.limitBalanceElement.innerHTML = `<span style="${balanceClass}"><strong>${balance.toFixed(2)} zł</strong></span>`;

        return balance;
      }

      this.limitBalanceElement.innerHTML = this.currentLimitData.message || 'Błąd pobierania bilansu';
      return null;

    } catch (error) {
      console.error('Error updating limit balance:', error);
      this.limitBalanceElement.innerHTML = 'Błąd pobierania bilansu';
      return null;
    }
  }
  async handleFormSubmit(event) {
    if (this.isSubmitting) {
      console.log('✅ Form was confirmed by user - sending...');
      return true;
    }

    const currentAmount = this.parseInputAmount();
    console.log('💰 Check amount:', currentAmount);

    if (currentAmount <= 0) {
      return true;
    }

    try {
      const balance = await this.updateLimitBalance(currentAmount);
      console.log('⚖️ Balance after adding amount:', balance);

      if (balance !== null && balance < 0) {
        event.preventDefault();
        event.stopPropagation();
        
        console.log('⚠️ Limit overdrawn - show modal warning');
        this.showLimitWarningModal();
        
        return false;
      }

      console.log('✅ Balance OK, proceeding...');
      return true;
      
    } catch (error) {
      console.error('❌ Error checking limit:', error);
      return true;
    }
  }

  handleModalConfirm() {
    try {
      this.isSubmitting = true;
      this.hideModal();
      this.submitForm();

    } catch (error) {
      console.error('❌ Approval modal error:', error);
      this.showErrorMessage('Wystąpił błąd podczas zapisywania. Spróbuj ponownie.');
      this.resetSubmissionState();
    }
  }

  hideModal() {
    try {
      const modal = document.getElementById('belowLimitModal');
      if (!modal) {
        console.warn('⚠️ Modal belowLimitModal has not found');
        return;
      }

      if (typeof $ !== 'undefined') {
        $('#belowLimitModal').modal('hide');
        return;
      }
      
    } catch (error) {
      console.error('❌ Error hidding modal:', error);
    }
  }

  submitForm() {
    try {
      if (!this.form) {
        throw new Error('Form not found');
      }

      console.log('📤 Sending form...');
      this.form.submit();
      
    } catch (error) {
      console.error('❌ Sending from error:', error);
      this.showErrorMessage('Nie udało się wysłać formularza. Spróbuj ponownie.');
      this.resetSubmissionState();
    }
  }

  showLimitWarningModal() {
    try {
      const modal = document.getElementById('belowLimitModal');
      if (!modal) {
        console.warn('⚠️ Modal not foun');
        return;
      }

      if (typeof $ !== 'undefined') {
        $('#belowLimitModal').modal('show');
        console.log('✅ Modal show by Bootstrap jQuery');
        return;
      }
      
    } catch (error) {
      console.error('❌ Showing modal error:', error);
    }
  }

  resetSubmissionState() {
    this.isSubmitting = false;
    console.log('🔄 Reset sending state');
  }

  showErrorMessage(message) {
    try {
      const errorContainer = document.querySelector('.error-message');
      if (errorContainer) {
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
        return;
      }
      
    } catch (error) {
      console.error('❌ Błąd podczas wyświetlania komunikatu:', error);
    }
  }
}
