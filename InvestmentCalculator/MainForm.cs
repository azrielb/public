using System;
using System.Drawing;
using System.Drawing.Drawing2D;
using System.Windows.Forms;

namespace InvestmentCalculator
{
    public partial class MainForm : Form
    {
        private Panel mainPanel;
        private Label titleLabel;
        private TextBox txtInitialInvestment;
        private TextBox txtMonthlyInvestment;
        private TextBox txtAnnualReturn;
        private TextBox txtYears;
        private CheckBox chkManagementFees;
        private TextBox txtManagementFees;
        private Panel managementFeesPanel;
        private Button btnCalculate;
        private Panel resultsPanel;
        private Label lblTotalDeposited;
        private Label lblTotalInterest;
        private Label lblTotalFees;
        private Label lblFinalTotal;

        public MainForm()
        {
            InitializeComponent();
            SetupUI();
        }

        private void SetupUI()
        {
            this.Text = "מחשבון ריבית דריבית - השקעות לעצלנים";
            this.Size = new Size(650, 900);
            this.StartPosition = FormStartPosition.CenterScreen;
            this.RightToLeft = RightToLeft.Yes;
            this.RightToLeftLayout = true;
            this.FormBorderStyle = FormBorderStyle.FixedSingle;
            this.MaximizeBox = false;
            this.AutoScroll = true;

            // Paint background
            this.Paint += MainForm_Paint;

            // Title
            titleLabel = new Label
            {
                Text = "מחשבון ריבית דריבית - השקעות לעצלנים",
                Font = new Font("Arial", 18, FontStyle.Bold),
                ForeColor = Color.Gold,
                AutoSize = false,
                Size = new Size(600, 40),
                Location = new Point(25, 20),
                TextAlign = ContentAlignment.MiddleCenter
            };
            this.Controls.Add(titleLabel);

            // Main white panel
            mainPanel = new Panel
            {
                BackColor = Color.White,
                Location = new Point(25, 70),
                Size = new Size(580, 780),
                BorderStyle = BorderStyle.None
            };
            mainPanel.Paint += MainPanel_Paint;
            this.Controls.Add(mainPanel);

            int yPos = 20;

            // Initial Investment
            AddLabel("סכום השקעה ראשוני (₪)", yPos);
            txtInitialInvestment = AddTextBox("", yPos + 25);
            yPos += 70;

            // Monthly Investment
            AddLabel("סכום השקעה חודשי (₪)", yPos);
            txtMonthlyInvestment = AddTextBox("", yPos + 25);
            yPos += 70;

            // Annual Return
            AddLabel("תשואה שנתית ממוצעת (%)", yPos);
            txtAnnualReturn = AddTextBox("", yPos + 25);
            yPos += 70;

            // Years
            AddLabel("מספר שנות השקעה", yPos);
            txtYears = AddTextBox("", yPos + 25);
            yPos += 70;

            // Management Fees Checkbox
            chkManagementFees = new CheckBox
            {
                Text = "להוסיף דמי ניהול",
                Font = new Font("Arial", 11, FontStyle.Regular),
                Location = new Point(380, yPos),
                Size = new Size(180, 30),
                Checked = false
            };
            chkManagementFees.CheckedChanged += ChkManagementFees_CheckedChanged;
            mainPanel.Controls.Add(chkManagementFees);
            yPos += 40;

            // Management Fees Panel (hidden by default)
            managementFeesPanel = new Panel
            {
                Location = new Point(30, yPos),
                Size = new Size(520, 60),
                Visible = false
            };
            mainPanel.Controls.Add(managementFeesPanel);

            Label lblManagementFeesPercent = new Label
            {
                Text = "אחוז דמי ניהול שנתי (%)",
                Font = new Font("Arial", 10, FontStyle.Regular),
                Location = new Point(350, 5),
                Size = new Size(160, 20),
                TextAlign = ContentAlignment.MiddleRight
            };
            managementFeesPanel.Controls.Add(lblManagementFeesPercent);

            txtManagementFees = new TextBox
            {
                Font = new Font("Arial", 12),
                Location = new Point(180, 0),
                Size = new Size(150, 30),
                Text = "0.5",
                ForeColor = Color.Black,
                TextAlign = HorizontalAlignment.Right,
                BorderStyle = BorderStyle.FixedSingle
            };
            managementFeesPanel.Controls.Add(txtManagementFees);

            yPos += 60;

            // Calculate Button
            btnCalculate = new Button
            {
                Text = "חשב",
                Font = new Font("Arial", 16, FontStyle.Bold),
                Size = new Size(520, 55),
                Location = new Point(30, yPos),
                FlatStyle = FlatStyle.Flat,
                BackColor = Color.Gold,
                ForeColor = Color.Black,
                Cursor = Cursors.Hand
            };
            btnCalculate.FlatAppearance.BorderSize = 0;
            btnCalculate.Click += BtnCalculate_Click;
            mainPanel.Controls.Add(btnCalculate);
            yPos += 70;

            // Results Panel
            resultsPanel = new Panel
            {
                Location = new Point(30, yPos),
                Size = new Size(520, 200),
                Visible = false
            };
            mainPanel.Controls.Add(resultsPanel);

            Label resultsTitle = new Label
            {
                Text = "תוצאות החישוב",
                Font = new Font("Arial", 14, FontStyle.Bold),
                ForeColor = Color.FromArgb(45, 134, 89),
                AutoSize = false,
                Size = new Size(520, 30),
                TextAlign = ContentAlignment.MiddleCenter
            };
            resultsPanel.Controls.Add(resultsTitle);

            lblTotalDeposited = AddResultLabel("סה\"כ הופקד:", 40);
            lblTotalInterest = AddResultLabel("רווח מריבית דריבית:", 75);
            lblTotalFees = AddResultLabel("דמי ניהול:", 110);
            lblFinalTotal = AddResultLabel("סה\"כ סופי:", 145, true);
        }

        private void AddLabel(string text, int yPos)
        {
            Label label = new Label
            {
                Text = text,
                Font = new Font("Arial", 11, FontStyle.Bold),
                Location = new Point(400, yPos),
                Size = new Size(160, 20),
                TextAlign = ContentAlignment.MiddleRight
            };
            mainPanel.Controls.Add(label);
        }

        private TextBox AddTextBox(string placeholder, int yPos)
        {
            TextBox txt = new TextBox
            {
                Font = new Font("Arial", 12),
                Location = new Point(30, yPos),
                Size = new Size(520, 30),
                Text = "",
                ForeColor = Color.Black,
                TextAlign = HorizontalAlignment.Right,
                BorderStyle = BorderStyle.FixedSingle
            };

            mainPanel.Controls.Add(txt);
            return txt;
        }

        private TextBox AddTextBoxWithSymbol(string symbol, string placeholder, int yPos)
        {
            TextBox txt = AddTextBox(placeholder, yPos);

            Label lblSymbol = new Label
            {
                Text = symbol,
                Font = new Font("Arial", 11, FontStyle.Bold),
                Location = new Point(txt.Right - 35, yPos + 3),
                Size = new Size(25, 20),
                TextAlign = ContentAlignment.MiddleCenter,
                ForeColor = Color.Gray
            };
            mainPanel.Controls.Add(lblSymbol);
            lblSymbol.BringToFront();

            return txt;
        }

        private Label AddResultLabel(string labelText, int yPos, bool isTotal = false)
        {
            Label lblText = new Label
            {
                Text = labelText,
                Font = new Font("Arial", isTotal ? 12 : 10, isTotal ? FontStyle.Bold : FontStyle.Regular),
                Location = new Point(350, yPos),
                Size = new Size(150, 25),
                TextAlign = ContentAlignment.MiddleRight,
                ForeColor = Color.FromArgb(102, 102, 102)
            };
            resultsPanel.Controls.Add(lblText);

            Label lblValue = new Label
            {
                Text = "0 ₪",
                Font = new Font("Arial", isTotal ? 13 : 10, FontStyle.Bold),
                Location = new Point(20, yPos),
                Size = new Size(200, 25),
                TextAlign = ContentAlignment.MiddleLeft,
                ForeColor = isTotal ? Color.FromArgb(26, 95, 58) : Color.FromArgb(45, 134, 89)
            };
            resultsPanel.Controls.Add(lblValue);

            return lblValue;
        }

        private void ChkManagementFees_CheckedChanged(object sender, EventArgs e)
        {
            managementFeesPanel.Visible = chkManagementFees.Checked;
        }

        private void BtnCalculate_Click(object sender, EventArgs e)
        {
            try
            {
                double initialInvestment = ParseTextBox(txtInitialInvestment);
                double monthlyInvestment = ParseTextBox(txtMonthlyInvestment);
                double annualReturn = ParseTextBox(txtAnnualReturn);
                double years = ParseTextBox(txtYears);

                if (years == 0)
                {
                    MessageBox.Show("נא להזין מספר שנות השקעה", "שגיאה", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                    return;
                }

                // Calculate compound interest
                double monthlyRate = annualReturn / 100 / 12;
                double months = years * 12;

                // Future value of initial investment
                double futureValue = initialInvestment * Math.Pow(1 + monthlyRate, months);

                // Future value of monthly investments (annuity)
                if (monthlyInvestment > 0 && monthlyRate > 0)
                {
                    futureValue += monthlyInvestment * ((Math.Pow(1 + monthlyRate, months) - 1) / monthlyRate);
                }
                else if (monthlyInvestment > 0)
                {
                    futureValue += monthlyInvestment * months;
                }

                // Total deposited
                double totalDeposited = initialInvestment + (monthlyInvestment * months);

                // Interest earned
                double totalInterest = futureValue - totalDeposited;

                // Management fees
                double managementFees = 0;
                if (chkManagementFees.Checked)
                {
                    double managementFeePercent = 0;
                    double.TryParse(txtManagementFees.Text, out managementFeePercent);
                    managementFees = futureValue * (managementFeePercent / 100) * years;
                }

                // Final total
                double finalTotal = futureValue - managementFees;

                // Display results
                lblTotalDeposited.Text = FormatCurrency(totalDeposited);
                lblTotalInterest.Text = FormatCurrency(totalInterest);
                lblTotalFees.Text = FormatCurrency(managementFees);
                lblFinalTotal.Text = FormatCurrency(finalTotal);

                resultsPanel.Visible = true;
            }
            catch (Exception ex)
            {
                MessageBox.Show("שגיאה בחישוב: " + ex.Message, "שגיאה", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private double ParseTextBox(TextBox txt)
        {
            if (string.IsNullOrWhiteSpace(txt.Text))
                return 0;

            double result;
            if (double.TryParse(txt.Text, out result))
                return result;

            return 0;
        }

        private string FormatCurrency(double amount)
        {
            return string.Format("{0:N0} ₪", amount);
        }

        private void MainForm_Paint(object sender, PaintEventArgs e)
        {
            // Draw gradient background
            using (LinearGradientBrush brush = new LinearGradientBrush(
                this.ClientRectangle,
                Color.FromArgb(26, 95, 58),
                Color.FromArgb(26, 79, 46),
                LinearGradientMode.Vertical))
            {
                e.Graphics.FillRectangle(brush, this.ClientRectangle);
            }
        }

        private void MainPanel_Paint(object sender, PaintEventArgs e)
        {
            // Draw rounded corners
            int radius = 20;
            GraphicsPath path = new GraphicsPath();
            path.AddArc(0, 0, radius, radius, 180, 90);
            path.AddArc(mainPanel.Width - radius, 0, radius, radius, 270, 90);
            path.AddArc(mainPanel.Width - radius, mainPanel.Height - radius, radius, radius, 0, 90);
            path.AddArc(0, mainPanel.Height - radius, radius, radius, 90, 90);
            path.CloseFigure();
            mainPanel.Region = new Region(path);
        }
    }
}
