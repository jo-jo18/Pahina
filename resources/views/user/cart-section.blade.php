<section id="cart-section" class="section">
    <h2 style="margin-bottom: 2rem; color: #1e293b;">🛒 Shopping Cart</h2>

    <div class="cart-select-all">
        <input type="checkbox" id="selectAll" class="cart-checkbox" onchange="toggleSelectAll()">
        <label for="selectAll">Select All Items</label>
        <span class="selected-count" id="selectedCount">0 selected</span>
    </div>

    <div class="table-container">
        <table id="cartTable">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Book</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cartBody">
            </tbody>
        </table>
    </div>

    <div class="payment-section">
        <div class="payment-title">
            <span>💳</span> Select Payment Method
        </div>
        <div class="payment-options">
            <div class="payment-option">
                <input type="radio" name="paymentMethod" id="paymentCOD" value="cod" checked onchange="togglePaymentDetails()">
                <label for="paymentCOD">
                    <span class="payment-icon">💵</span>
                    <span class="payment-name">Cash on Delivery</span>
                    <span class="payment-desc">Pay when you receive your books</span>
                </label>
            </div>
            <div class="payment-option">
                <input type="radio" name="paymentMethod" id="paymentBank" value="bank" onchange="togglePaymentDetails()">
                <label for="paymentBank">
                    <span class="payment-icon">🏦</span>
                    <span class="payment-name">Bank Transfer</span>
                    <span class="payment-desc">Pay via bank transfer</span>
                </label>
            </div>
        </div>

        <div class="bank-transfer-form" id="bankTransferSection">
            <div class="form-title">
                <span>📝</span> Complete Your Bank Transfer
            </div>
            
            <div class="amount-display" id="amountDisplay">
                <div class="amount-label">Exact Amount to Pay:</div>
                <div class="amount-value" id="bankAmount">$0.00</div>
                <div class="amount-updated" id="amountUpdated">
                    <span>✓</span> Updates in real-time
                </div>
            </div>

            <form id="bankTransferForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="senderBank">Your Bank Name *</label>
                        <input type="text" id="senderBank" placeholder="e.g., BDO, BPI, Metrobank" required>
                    </div>
                    <div class="form-group">
                        <label for="senderAccountName">Account Name *</label>
                        <input type="text" id="senderAccountName" placeholder="Your full name as in bank" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="senderAccountNumber">Account Number *</label>
                        <input type="text" id="senderAccountNumber" placeholder="Your bank account number" required>
                    </div>
                    <div class="form-group">
                        <label for="referenceNumber">Reference Number *</label>
                        <input type="text" id="referenceNumber" placeholder="Transfer reference/transaction ID" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="transferDate">Transfer Date *</label>
                        <input type="date" id="transferDate" required>
                    </div>
                    <div class="form-group">
                        <label for="transferTime">Transfer Time</label>
                        <input type="time" id="transferTime">
                    </div>
                </div>

                <div class="form-group">
                    <label for="transferAmount">Transfer Amount *</label>
                    <input type="number" id="transferAmount" step="0.01" placeholder="Enter exact amount transferred" readonly style="background: #f0f9ff; color: #3b82f6; font-weight: bold; border-color: #3b82f6;">
                    <small style="color: #64748b; font-size: 0.8rem;">Amount is automatically set to the total above</small>
                </div>

                <div class="form-group">
                    <label for="paymentProof">Upload Payment Proof (Optional)</label>
                    <input type="file" id="paymentProof" accept="image/*,.pdf" onchange="previewPaymentProof(this)">
                    <div class="file-upload-note">Upload screenshot or photo of transfer confirmation (JPG, PNG, PDF)</div>
                    <img id="proofPreview" class="image-preview" src="#" alt="Payment proof preview" style="max-width: 100%; margin-top: 0.5rem;">
                </div>

                <div class="form-group">
                    <label for="additionalNotes">Additional Notes</label>
                    <textarea id="additionalNotes" rows="2" placeholder="Any additional information about your transfer..."></textarea>
                </div>
            </form>
        </div>

        <div class="bank-details active" id="codDetails">
            <div class="cod-note">
                <strong>💵 Cash on Delivery</strong>
                <p style="margin-top: 0.5rem; color: #475569;">Pay exactly <span id="codAmount">$0.00</span> in cash when your books are delivered. Please prepare the exact amount.</p>
            </div>
            
            <div class="form-group">
                <label for="codRecipientName">Recipient Full Name *</label>
                <input type="text" id="codRecipientName" placeholder="Enter recipient's full name" required>
                <small style="color: #64748b;">This will be printed on the delivery label</small>
            </div>
            
            <div class="form-group">
                <label for="codAddress">Complete Delivery Address *</label>
                <textarea id="codAddress" rows="3" placeholder="Street address, barangay, city, province, zip code" required></textarea>
                <small style="color: #64748b;">Please provide your complete address for delivery</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="codPhone">Contact Number *</label>
                    <input type="tel" id="codPhone" placeholder="e.g., 09123456789" required>
                </div>
                <div class="form-group">
                    <label for="codDeliveryInstructions">Delivery Instructions (Optional)</label>
                    <input type="text" id="codDeliveryInstructions" placeholder="e.g., Landmark, gate code, etc.">
                </div>
            </div>
            
            <p style="margin-top: 1rem; font-size: 0.9rem; color: #64748b;">
                <span>📍 </span>Delivery within 3-5 business days
            </p>
        </div>

        <div class="order-summary">
            <h4 style="color: #1e293b; margin-bottom: 1rem;">Order Summary</h4>
            <div class="summary-row">
                <span>Selected Items:</span>
                <span id="selectedItemsCount">0 items</span>
            </div>
            <div class="summary-row">
                <span>Subtotal (selected items):</span>
                <span id="summarySubtotal">$0.00</span>
            </div>
            <div class="summary-row">
                <span>Shipping Fee:</span>
                <span id="summaryShipping">$5.00</span>
            </div>
            <div class="summary-row total">
                <span>Total Amount to Pay:</span>
                <span id="summaryTotal">$0.00</span>
            </div>
        </div>
    </div>

    <div style="text-align: right; margin-top: 2rem;">
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <button class="btn btn-secondary" onclick="clearCart()">Clear Cart</button>
            <button class="btn btn-primary" onclick="proceedToCheckout()" style="padding: 1rem 3rem;" id="checkoutBtn">Proceed to Checkout</button>
        </div>
    </div>
</section>