from flask_wtf import FlaskForm
from wtforms import StringField, PasswordField, BooleanField, SubmitField
from wtforms.validators import DataRequired

class EnableForm(FlaskForm):
    card = StringField('Card', validators=[DataRequired()])
    year = StringField('Year')
    month = StringField('Month')
    day = StringField('Day')
    hour = StringField('Hour')
    minute = StringField('Minute')