using System;
using System.Drawing;
using System.Windows.Forms;

namespace ShpitzerCalculator
{
    public class MainForm : Form
    {
        private TextBox txtLoanAmount;
        private TextBox txtAnnualRate;
        private TextBox txtYears;
        private TextBox txtMonths;
        private Button btnCalculate;
        private Button btnShowSchedule;
        private Label lblMonthlyPayment;
        private Label lblTotalPayment;
        private Label lblTotalInterest;
        private DataGridView gridSchedule;
        private Panel resultsPanel;

        // Calculated values
        private double monthlyPayment;
        private double loanAmount;
        private double monthlyRate;
        private int totalMonths;

        public MainForm()
        {
            InitializeComponent();
        }

        private void InitializeComponent()
        {
            this.Text = "מחשבון הלוואה - שפיצר";
            this.Size = new Size(650, 700);
            this.StartPosition = FormStartPosition.CenterScreen;
            this.Font = new Font("Segoe UI", 10F);
            this.RightToLeft = RightToLeft.Yes;
            this.RightToLeftLayout = true;

            int y = 20;
            int labelX = 20;
            int inputX = 200;
            int inputWidth = 150;

            // Loan Amount
            var lblAmount = new Label { Text = "סכום ההלוואה:", Location = new Point(labelX, y), AutoSize = true };
            txtLoanAmount = new TextBox { Location = new Point(inputX, y - 3), Width = inputWidth, RightToLeft = RightToLeft.No };
            this.Controls.Add(lblAmount);
            this.Controls.Add(txtLoanAmount);

            y += 35;

            // Annual Interest Rate
            var lblRate = new Label { Text = "ריבית שנתית (%):", Location = new Point(labelX, y), AutoSize = true };
            txtAnnualRate = new TextBox { Location = new Point(inputX, y - 3), Width = inputWidth, RightToLeft = RightToLeft.No };
            this.Controls.Add(lblRate);
            this.Controls.Add(txtAnnualRate);

            y += 35;

            // Loan Period - Years
            var lblYears = new Label { Text = "תקופה - שנים:", Location = new Point(labelX, y), AutoSize = true };
            txtYears = new TextBox { Location = new Point(inputX, y - 3), Width = 60, Text = "0", RightToLeft = RightToLeft.No };
            this.Controls.Add(lblYears);
            this.Controls.Add(txtYears);

            // Loan Period - Months
            var lblMonths = new Label { Text = "חודשים:", Location = new Point(inputX + 70, y), AutoSize = true };
            txtMonths = new TextBox { Location = new Point(inputX + 140, y - 3), Width = 60, Text = "0", RightToLeft = RightToLeft.No };
            this.Controls.Add(lblMonths);
            this.Controls.Add(txtMonths);

            y += 45;

            // Calculate Button
            btnCalculate = new Button
            {
                Text = "חשב",
                Location = new Point(labelX, y),
                Size = new Size(120, 35),
                BackColor = Color.FromArgb(0, 120, 215),
                ForeColor = Color.White,
                FlatStyle = FlatStyle.Flat
            };
            btnCalculate.Click += BtnCalculate_Click;
            this.Controls.Add(btnCalculate);

            // Show Schedule Button
            btnShowSchedule = new Button
            {
                Text = "הצג לוח סילוקין",
                Location = new Point(150, y),
                Size = new Size(180, 35),
                Enabled = false,
                FlatStyle = FlatStyle.Flat
            };
            btnShowSchedule.Click += BtnShowSchedule_Click;
            this.Controls.Add(btnShowSchedule);

            y += 55;

            // Results Panel
            resultsPanel = new Panel
            {
                Location = new Point(labelX, y),
                Size = new Size(380, 120),
                BorderStyle = BorderStyle.FixedSingle,
                Visible = false
            };

            lblMonthlyPayment = new Label
            {
                Text = "תשלום חודשי:",
                Location = new Point(10, 15),
                AutoSize = true,
                Font = new Font("Segoe UI", 12F, FontStyle.Bold)
            };
            resultsPanel.Controls.Add(lblMonthlyPayment);

            lblTotalPayment = new Label
            {
                Text = "סה\"כ תשלום:",
                Location = new Point(10, 50),
                AutoSize = true
            };
            resultsPanel.Controls.Add(lblTotalPayment);

            lblTotalInterest = new Label
            {
                Text = "סה\"כ ריבית:",
                Location = new Point(10, 80),
                AutoSize = true
            };
            resultsPanel.Controls.Add(lblTotalInterest);

            this.Controls.Add(resultsPanel);

            y += 135;

            // Payment Schedule Grid
            gridSchedule = new DataGridView
            {
                Location = new Point(labelX, y),
                Size = new Size(590, 300),
                ReadOnly = true,
                AllowUserToAddRows = false,
                AllowUserToDeleteRows = false,
                AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill,
                Visible = false,
                ColumnHeadersHeightSizeMode = DataGridViewColumnHeadersHeightSizeMode.AutoSize,
                RightToLeft = RightToLeft.Yes
            };
            this.Controls.Add(gridSchedule);

            // Set up columns
            gridSchedule.Columns.Add("Month", "חודש");
            gridSchedule.Columns.Add("Payment", "תשלום");
            gridSchedule.Columns.Add("Principal", "קרן");
            gridSchedule.Columns.Add("Interest", "ריבית");
            gridSchedule.Columns.Add("Balance", "יתרה");

            gridSchedule.Columns["Month"].Width = 60;
            gridSchedule.Columns["Payment"].Width = 100;
            gridSchedule.Columns["Principal"].Width = 100;
            gridSchedule.Columns["Interest"].Width = 100;
            gridSchedule.Columns["Balance"].Width = 120;
        }

        private void BtnCalculate_Click(object sender, EventArgs e)
        {
            try
            {
                // Parse inputs
                if (!double.TryParse(txtLoanAmount.Text, out loanAmount) || loanAmount <= 0)
                {
                    MessageBox.Show("אנא הזן סכום הלוואה תקין.", "שגיאת קלט", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return;
                }

                double annualRate;
                if (!double.TryParse(txtAnnualRate.Text, out annualRate) || annualRate < 0)
                {
                    MessageBox.Show("אנא הזן ריבית תקינה.", "שגיאת קלט", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return;
                }

                int years, months;
                if (!int.TryParse(txtYears.Text, out years)) years = 0;
                if (!int.TryParse(txtMonths.Text, out months)) months = 0;

                totalMonths = years * 12 + months;
                if (totalMonths <= 0)
                {
                    MessageBox.Show("אנא הזן תקופת הלוואה תקינה.", "שגיאת קלט", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return;
                }

                // Calculate using Shpitzer formula
                monthlyRate = annualRate / 100 / 12;

                if (monthlyRate == 0)
                {
                    // No interest case
                    monthlyPayment = loanAmount / totalMonths;
                }
                else
                {
                    // Shpitzer formula: PMT = P * [r(1+r)^n] / [(1+r)^n - 1]
                    double factor = Math.Pow(1 + monthlyRate, totalMonths);
                    monthlyPayment = loanAmount * (monthlyRate * factor) / (factor - 1);
                }

                double totalPayment = monthlyPayment * totalMonths;
                double totalInterest = totalPayment - loanAmount;

                // Display results
                lblMonthlyPayment.Text = "תשלום חודשי: " + monthlyPayment.ToString("N2");
                lblTotalPayment.Text = "סה\"כ תשלום: " + totalPayment.ToString("N2");
                lblTotalInterest.Text = "סה\"כ ריבית: " + totalInterest.ToString("N2");

                resultsPanel.Visible = true;
                btnShowSchedule.Enabled = true;
                gridSchedule.Visible = false;
            }
            catch (Exception ex)
            {
                MessageBox.Show("שגיאה: " + ex.Message, "שגיאת חישוב", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void BtnShowSchedule_Click(object sender, EventArgs e)
        {
            gridSchedule.Rows.Clear();
            double balance = loanAmount;

            for (int month = 1; month <= totalMonths; month++)
            {
                double interestPayment = balance * monthlyRate;
                double principalPayment = monthlyPayment - interestPayment;
                balance -= principalPayment;

                // Prevent negative balance due to rounding
                if (balance < 0) balance = 0;

                gridSchedule.Rows.Add(
                    month,
                    monthlyPayment.ToString("N2"),
                    principalPayment.ToString("N2"),
                    interestPayment.ToString("N2"),
                    balance.ToString("N2")
                );
            }

            gridSchedule.Visible = true;
        }

        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new MainForm());
        }
    }
}
