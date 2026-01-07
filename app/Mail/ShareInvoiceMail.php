<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShareInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @param int $orderId
     */
    public function __construct($orderId)
    {
        $this->order = Order::with(['orderCalculationStmt', 'restaurant', 'customer'])->findOrFail($orderId);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Invoice for Order No: #' . $this->order->id,
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Generate the PDF
        $pdf = \PDF::loadView('email.test', ['order' => $this->order])
        ->setOption('font', 'DejaVu Sans');

        // Return the email with the view and the PDF attachment
        return $this->view('email.test')
                    ->with(['order' => $this->order]) // Pass data to the view
                    ->attachData($pdf->output(), 'invoice.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Return any additional attachments if necessary, otherwise leave this empty.
        return [];
    }
}

